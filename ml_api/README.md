# Student Risk Prediction ML API

Flask microservice API for predicting student risk levels using machine learning.

## Setup Instructions

### 1. Install Python Dependencies

```bash
pip install -r requirements.txt
```

Or if using a virtual environment:

```bash
# Create virtual environment (if not already created)
python -m venv venv

# Activate virtual environment
# On Windows:
venv\Scripts\activate
# On Linux/Mac:
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt
```

### 2. Generate the Model

Before running the API, you need to generate the `student_risk_model.pkl` file:

1. Open and run `model/student_risk_model.ipynb` in Jupyter Notebook
2. The notebook will generate `student_risk_model.pkl` in the model directory
3. Copy `student_risk_model.pkl` to the `ml_api` directory:

```bash
# Copy model file to ml_api directory
copy model\student_risk_model.pkl ml_api\student_risk_model.pkl
```

Or if the model is saved in the model directory, you can update the `model_path` in `app.py`.

### 3. Run the API

```bash
python app.py
```

The API will start on `http://localhost:5000`

## API Endpoints

### Health Check
```
GET /health
```

Returns API status and model loading information.

**Response:**
```json
{
  "status": "ok",
  "model_loaded": true,
  "model_path": "...",
  "model_exists": true
}
```

### Single Prediction
```
POST /predict
```

**Request Body:**
```json
{
  "avg_watch_pct": 72.5,
  "completion_rate": 0.75,
  "avg_quiz_score": 65.0,
  "days_inactive": 6,
  "lessons_completed": 3
}
```

**Response:**
```json
{
  "success": true,
  "risk_level": 1,
  "risk_label": "May Struggle",
  "confidence": 0.943,
  "probabilities": {
    "will_pass": 0.05,
    "may_struggle": 0.943,
    "needs_help": 0.007
  }
}
```

### Batch Prediction
```
POST /batch-predict
```

**Request Body:**
```json
{
  "students": [
    {
      "student_id": 1,
      "avg_watch_pct": 75.0,
      "completion_rate": 0.8,
      "avg_quiz_score": 70.0,
      "days_inactive": 5,
      "lessons_completed": 4
    },
    {
      "student_id": 2,
      "avg_watch_pct": 60.0,
      "completion_rate": 0.6,
      "avg_quiz_score": 55.0,
      "days_inactive": 10,
      "lessons_completed": 2
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "predictions": [
    {
      "student_id": 1,
      "risk_level": 0,
      "risk_label": "Will Pass",
      "confidence": 0.95,
      "probabilities": {
        "will_pass": 0.95,
        "may_struggle": 0.04,
        "needs_help": 0.01
      }
    },
    {
      "student_id": 2,
      "risk_level": 1,
      "risk_label": "May Struggle",
      "confidence": 0.87,
      "probabilities": {
        "will_pass": 0.10,
        "may_struggle": 0.87,
        "needs_help": 0.03
      }
    }
  ]
}
```

## Risk Levels

- **0 - Will Pass**: Student is performing well and likely to succeed
- **1 - May Struggle**: Student may need some support
- **2 - Needs Help**: Student requires immediate intervention

## Integration with Laravel

From your Laravel application, you can call this API using Guzzle or cURL:

```php
use Illuminate\Support\Facades\Http;

$response = Http::post('http://localhost:5000/predict', [
    'avg_watch_pct' => 72.5,
    'completion_rate' => 0.75,
    'avg_quiz_score' => 65.0,
    'days_inactive' => 6,
    'lessons_completed' => 3
]);

$result = $response->json();
```

## Troubleshooting

### Model Not Found Error
- Ensure `student_risk_model.pkl` exists in the `ml_api` directory
- Check the model path in `app.py` if the model is in a different location

### Port Already in Use
- Change the port in `app.py`: `app.run(host='0.0.0.0', port=5001, debug=True)`
- Update Laravel API calls to use the new port

### CORS Issues
- The API includes `flask-cors` which should handle CORS automatically
- If issues persist, check that `CORS(app)` is enabled in `app.py`
