# Deploy ShopZone to Render (free)

You do **not** need PostgreSQL on your computer. Render provides it.

## 1. Push this project to GitHub

```bash
git init
git add .
git commit -m "ShopZone Laravel for Render"
# create repo on GitHub, then:
git remote add origin https://github.com/YOUR_USER/shopzone.git
git push -u origin main
```

## 2. Create Postgres on Render

1. https://dashboard.render.com → **New** → **PostgreSQL**
2. Name: `shopzone-db`
3. Plan: **Free**
4. Create → open the DB → copy **Internal Database URL**

## 3. Create Web Service

1. **New** → **Web Service** → connect your GitHub repo
2. Settings:

| Field | Value |
|--------|--------|
| Runtime | **Docker** |
| Branch | `main` |
| Instance | **Free** |

3. Environment variables:

| Key | Value |
|-----|--------|
| `APP_KEY` | Run on your PC: `php artisan key:generate --show` (after `composer install --no-scripts`). Paste `base64:...` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `true` (for first test) |
| `DB_CONNECTION` | `pgsql` |
| `DATABASE_URL` | Internal URL from step 2 |
| `LOG_CHANNEL` | `stderr` |
| `SESSION_DRIVER` | `file` |
| `CACHE_STORE` | `file` |
| `QUEUE_CONNECTION` | `sync` |

4. **Create Web Service** → wait for build

## 4. Test

Open `https://your-service.onrender.com`

Login: **admin** / **admin**

## Notes

- Free service **sleeps** after ~15 min idle (first load can be slow).
- Free Postgres may expire after ~30 days (OK for testing).
- No local Postgres needed.
