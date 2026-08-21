# Pharmacovigilance Alert System

Módulo de farmacovigilancia para una farmacia magistral (compounding pharmacy), que permite identificar y notificar a los clientes que compraron medicamentos asociados a un número de lote específico dentro de un rango de fechas.

Desarrollado como prueba técnica para LifeFile.

## Tech Stack

| Componente | Tecnología / Versión |
|---|---|
| Backend | PHP 8.2 + Laravel 12 |
| Frontend | Vue 3 (Composition API) + Vite |
| Base de datos | MySQL 8.0 (Docker) |
| Autenticación | Laravel Sanctum (tokens) |
| Colas | Laravel Queues (driver `database`) |
| Correo | Laravel Mail (SMTP vía Mailtrap para pruebas) |
| Estilos | Tailwind CSS |
| Alertas UI | SweetAlert2 |

---

## 1. Instrucciones de configuración (Setup)

### Requisitos previos

- PHP 8.2
- Composer
- Node.js 20+ y npm
- Docker y Docker Compose

### Pasos

**1. Clonar el repositorio**

```bash
git clone https://github.com/cuellar037/pharmacovigilance-alert-system.git
cd pharmacovigilance-alert-system
```

**2. Instalar dependencias de PHP**

```bash
composer install
```

**3. Configurar variables de entorno**

```bash
cp .env.example .env
php artisan key:generate
```

Verifica que las siguientes variables coincidan con tu entorno (los valores por defecto ya están alineados con el `docker-compose.yml` incluido):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmacovigilance
DB_USERNAME=root
DB_PASSWORD=123456

QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
```

> Para probar el envío de correos, crea una cuenta gratuita en [Mailtrap](https://mailtrap.io) (Email Testing → Inbox → SMTP Settings → Laravel) y pega tus credenciales reales en `MAIL_USERNAME` y `MAIL_PASSWORD`.

**4. Levantar la base de datos con Docker**

```bash
docker compose up -d
```

Esto levanta dos contenedores:
- **MySQL 8.0** en el puerto `3306`
- **phpMyAdmin** en `http://localhost:8080` (usuario `root`, password `123456`) para inspeccionar la base visualmente

**5. Correr migraciones y seeders**

```bash
php artisan migrate:fresh --seed
```

Esto crea todas las tablas y las llena con datos de prueba (ver sección de Seed Data más abajo).

**6. Instalar dependencias de frontend**

```bash
npm install
```

**7. Levantar el proyecto**

En una terminal, levanta el backend:

```bash
php artisan serve
```

En otra terminal, levanta el build de assets de Vite:

```bash
npm run dev
```

En una tercera terminal, levanta el worker de colas (necesario para que las alertas por email se procesen):

```bash
php artisan queue:work
```

**8. Acceder a la aplicación**

Abre `http://127.0.0.1:8000` en tu navegador.

**Credenciales de prueba:**
```
Usuario: admin
Contraseña: 123456
```

---

## 2. Decisiones de diseño

**Frontend integrado en el mismo proyecto Laravel, no como SPA separada.** El frontend Vue 3 se sirve desde el mismo backend Laravel usando el plugin oficial `laravel-vite-plugin`, en vez de correr como un proyecto Node completamente independiente en otro puerto. Vue Router sigue manejando toda la navegación en modo `history` en el navegador — es una SPA real, solo que Laravel sirve el punto de entrada inicial a través de una vista Blade con `Route::fallback()`. Esto simplifica el despliegue (un solo repositorio, menos configuración de CORS entre orígenes) sin sacrificar la separación de responsabilidades entre frontend y backend.

**Un solo endpoint de búsqueda en vez de dos.** El PDF especifica `GET /api/medications/search` y `GET /api/orders` como endpoints separados. Se optó por fusionarlos en un único endpoint `GET /api/orders?lot=&start_date=&end_date=`, ya que ambos casos de uso (buscar por lote, filtrar por fecha) resuelven la misma necesidad de negocio y evita duplicar la lógica de consulta en dos controladores distintos.

**Autenticación por token con Laravel Sanctum**, en vez de Passport (OAuth2 completo) o sesiones tradicionales. Sanctum es la opción recomendada por Laravel para APIs consumidas por SPAs, y es suficiente para el alcance de este proyecto. Los tokens tienen expiración configurada (`config/sanctum.php`), en vez de ser indefinidos por defecto, como medida de seguridad adicional.

**Sistema de alertas basado en colas (queues) en vez de envío síncrono.** Enviar un correo dentro del ciclo normal de la petición HTTP bloquea al usuario mientras el servidor SMTP responde, lo cual es especialmente problemático en envíos masivos (bulk). Se implementó `SendMedicationAlertJob`, que se despacha a una cola (driver `database`) y se procesa en segundo plano, con:
- 3 reintentos automáticos con backoff progresivo (10s / 30s / 60s) ante fallos transitorios (ej. SMTP caído)
- Actualización del estado de la alerta (`pending` / `sent` / `failed`) y registro del mensaje de error exacto si el envío falla definitivamente
- Espaciado manual entre envíos (`->delay()`) al disparar alertas masivas, para respetar los límites de tasa (rate limits) del proveedor de correo

**Manejo de errores JSON centralizado.** Todas las rutas bajo `/api/*` devuelven errores en formato JSON consistente (404, 401, 422, 500) mediante un manejador de excepciones configurado en `bootstrap/app.php`, en vez de las páginas de error HTML por defecto de Laravel — apropiado para una API consumida por un frontend.

**Export CSV con streaming.** El endpoint `GET /api/orders/export` genera el archivo CSV escribiendo directamente al flujo de salida HTTP (`response()->streamDownload()`) en vez de construir el archivo completo en memoria, para que la solución escale mejor ante un volumen mayor de resultados.

---

## 3. Supuestos

- Se asume un único rol de usuario (personal autorizado de farmacovigilancia); no se implementó control de acceso basado en roles (bonus opcional no incluido en esta entrega).
- El campo `lot_number` se trata como texto libre (no como catálogo cerrado de lotes), ya que el PDF no especifica una lista predefinida de lotes válidos.
- El rango de fechas por defecto al buscar (sin especificar `start_date`/`end_date`) es de los últimos 30 días, según lo indicado en el punto 3.2 del enunciado.
- Un cliente puede tener múltiples órdenes, y una orden puede contener múltiples medicamentos (relación resuelta mediante la tabla intermedia `order_items`).
- El envío de SMS (mencionado como bonus opcional en el punto 3.5) no fue implementado; el sistema de alertas cubre únicamente el canal de correo electrónico, que es el requisito obligatorio.
- Las pruebas de envío de correo se realizaron contra un entorno sandbox (Mailtrap), no contra un servidor SMTP de producción real.
- No se implementaron tests automatizados exhaustivos (bonus opcional) dado el alcance de tiempo de la prueba (3 días); el proyecto fue validado manualmente end-to-end en cada etapa de construcción.

---

## 4. Esquema de base de datos / Migraciones

### Diagrama de relaciones

```
customers ──┬── orders ──┬── order_items ──── medications
            │            │
            │            └── alerts
            │
            └── alerts

users ──── alerts (usuario que disparó la alerta)
```

### Tablas

**`users`**
| Campo | Tipo |
|---|---|
| id | bigint, PK |
| username | string |
| email | string, unique |
| password | string (hashed) |

**`customers`**
| Campo | Tipo |
|---|---|
| id | bigint, PK |
| name | string |
| email | string, unique |
| phone | string, nullable |

**`medications`**
| Campo | Tipo |
|---|---|
| id | bigint, PK |
| name | string |
| lot_number | string, indexado |

**`orders`**
| Campo | Tipo |
|---|---|
| id | bigint, PK |
| customer_id | FK → customers, cascade on delete |
| purchase_date | date, indexado |

**`order_items`** (tabla intermedia orden ↔ medicamento)
| Campo | Tipo |
|---|---|
| id | bigint, PK |
| order_id | FK → orders, cascade on delete |
| medication_id | FK → medications, cascade on delete |

**`alerts`**
| Campo | Tipo |
|---|---|
| id | bigint, PK |
| customer_id | FK → customers |
| order_id | FK → orders |
| user_id | FK → users (quién disparó la alerta) |
| status | string (`pending` / `sent` / `failed`) |
| error_message | text, nullable |
| sent_at | timestamp, nullable |

Todas las migraciones están versionadas en `database/migrations/` y se aplican con `php artisan migrate`.

---

## 5. Datos de ejemplo (Seed Data)

El comando `php artisan migrate:fresh --seed` genera:

- **1 usuario administrador** (`admin` / `123456`) para autenticarse en el sistema
- **1 medicamento objetivo**: *Ibuprofeno 400mg*, lote `951357` — el caso de prueba principal del enunciado
- **5 medicamentos adicionales** con lotes distintos generados aleatoriamente (para probar que el filtro de búsqueda excluye correctamente lo que no corresponde)
- **15 clientes** con datos ficticios realistas (nombre, email, teléfono)
- **3 escenarios de órdenes**, diseñados específicamente para validar el filtro de búsqueda:
  1. **6 órdenes** con el lote `951357`, con fecha de compra dentro de los últimos 30 días → deben aparecer al buscar el lote `951357` con el rango por defecto
  2. **3 órdenes** con el mismo lote `951357`, pero con fecha de compra de hace 60-120 días → deben quedar excluidas del rango por defecto (validan el filtro de fecha)
  3. **10 órdenes** con medicamentos de otros lotes → nunca deben aparecer al buscar específicamente el lote `951357` (validan el filtro de lote)

Esta estrategia permite verificar de forma objetiva que la búsqueda funciona: al buscar el lote `951357` sin especificar fechas, el resultado esperado es **exactamente 6 órdenes**.

---

## API Endpoints

| Método | Endpoint | Descripción |
|---|---|---|
| POST | `/api/login` | Autenticación (username + password), devuelve token |
| POST | `/api/logout` | Revoca el token actual |
| GET | `/api/orders?lot=&start_date=&end_date=` | Búsqueda de órdenes por lote y rango de fechas |
| GET | `/api/orders/export?lot=&start_date=&end_date=` | Exporta los resultados de búsqueda como CSV |
| GET | `/api/orders/{id}` | Detalle de una orden específica |
| GET | `/api/customers/{id}` | Detalle de un cliente y su historial de órdenes |
| POST | `/api/alerts/send` | Envío de alerta(s) — individual o masivo |

Todas las rutas, excepto `/login`, requieren el header `Authorization: Bearer {token}`.