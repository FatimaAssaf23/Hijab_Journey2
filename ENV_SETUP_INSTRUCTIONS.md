# Environment Configuration Setup

## Add ML_API_URL to .env File

Add the following line to your `.env` file:

```env
ML_API_URL=http://localhost:5000
```

### Steps:

1. **Open your `.env` file** in the root directory of your Laravel project
2. **Add the following line** (you can add it in the "Third Party Services" section or at the end):

```env
# ML Prediction API Configuration
ML_API_URL=http://localhost:5000
```

3. **Save the file**

### Example .env file structure:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# ... other configurations ...

# ML Prediction API Configuration
ML_API_URL=http://localhost:5000
```

### For Production:

If you're deploying to production, update the URL to match your production Python API:

```env
ML_API_URL=http://your-production-server:5000
```

Or if using a domain:

```env
ML_API_URL=https://ml-api.yourdomain.com
```

### Verify Configuration:

After adding the configuration, you can verify it's working by:

1. **Check if the service can read it:**
   ```php
   // In tinker or a test route
   echo config('services.ml_api.url');
   // Should output: http://localhost:5000
   ```

2. **Test the ML API connection:**
   ```bash
   curl http://localhost:5000/health
   ```

### Configuration File:

The configuration has also been added to `config/services.php` for better management. The service uses `config('services.ml_api.url')` which reads from your `.env` file. This follows Laravel best practices and works better with configuration caching.

### Important Notes:

- The default value in `MLPredictionService` is `http://localhost:5000`, so if you don't add this to `.env`, it will still work with the default
- Make sure the Python ML API is running on the specified port before using the service
- If you change the port in the Python API, update this value accordingly
