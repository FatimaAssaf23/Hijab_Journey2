from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import pandas as pd
import numpy as np
import os

app = Flask(__name__)
CORS(app)  # Allow Laravel to call this API

# Load model once at startup
model_path = os.path.join(os.path.dirname(__file__), 'student_risk_model.pkl')
model = None

try:
    if os.path.exists(model_path):
        model = joblib.load(model_path)
        print(f"✅ Model loaded successfully from {model_path}")
    else:
        print(f"⚠️ Warning: Model file not found at {model_path}")
        print("   Please ensure student_risk_model.pkl exists in the ml_api directory")
except Exception as e:
    print(f"❌ Error loading model: {str(e)}")

@app.route('/predict', methods=['POST'])
def predict():
    """
    Expects JSON:
    {
        "avg_watch_pct": 72.5,
        "completion_rate": 0.75,
        "avg_quiz_score": 65.0,
        "days_inactive": 6,
        "lessons_completed": 3
    }
    """
    if model is None:
        return jsonify({
            'success': False,
            'error': 'Model not loaded. Please ensure student_risk_model.pkl exists.'
        }), 500
    
    try:
        data = request.get_json()
        
        # Validate required fields
        required_fields = ['avg_watch_pct', 'completion_rate', 'avg_quiz_score', 'days_inactive', 'lessons_completed']
        for field in required_fields:
            if field not in data:
                return jsonify({
                    'success': False,
                    'error': f'Missing required field: {field}'
                }), 400
        
        # Create DataFrame
        student_data = pd.DataFrame({
            'avg_watch_pct': [data['avg_watch_pct']],
            'completion_rate': [data['completion_rate']],
            'avg_quiz_score': [data['avg_quiz_score']],
            'days_inactive': [data['days_inactive']],
            'lessons_completed': [data['lessons_completed']]
        })
        
        # Predict
        prediction = int(model.predict(student_data)[0])
        probabilities = model.predict_proba(student_data)[0]
        
        # Risk labels
        risk_labels = {
            0: 'Will Pass',
            1: 'May Struggle',
            2: 'Needs Help'
        }
        
        return jsonify({
            'success': True,
            'risk_level': prediction,
            'risk_label': risk_labels[prediction],
            'confidence': float(probabilities[prediction]),
            'probabilities': {
                'will_pass': float(probabilities[0]),
                'may_struggle': float(probabilities[1]),
                'needs_help': float(probabilities[2])
            }
        })
        
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 400

@app.route('/batch-predict', methods=['POST'])
def batch_predict():
    """
    Expects JSON:
    {
        "students": [
            {"student_id": 1, "avg_watch_pct": 75, "completion_rate": 0.8, "avg_quiz_score": 70, "days_inactive": 5, "lessons_completed": 4},
            {"student_id": 2, "avg_watch_pct": 60, "completion_rate": 0.6, "avg_quiz_score": 55, "days_inactive": 10, "lessons_completed": 2}
        ]
    }
    """
    if model is None:
        return jsonify({
            'success': False,
            'error': 'Model not loaded. Please ensure student_risk_model.pkl exists.'
        }), 500
    
    try:
        data = request.get_json()
        
        if 'students' not in data:
            return jsonify({
                'success': False,
                'error': 'Missing required field: students'
            }), 400
        
        students = data['students']
        required_fields = ['avg_watch_pct', 'completion_rate', 'avg_quiz_score', 'days_inactive', 'lessons_completed']
        
        results = []
        for student in students:
            # Validate required fields
            for field in required_fields:
                if field not in student:
                    results.append({
                        'student_id': student.get('student_id'),
                        'error': f'Missing required field: {field}'
                    })
                    continue
            
            try:
                student_data = pd.DataFrame({
                    'avg_watch_pct': [student['avg_watch_pct']],
                    'completion_rate': [student['completion_rate']],
                    'avg_quiz_score': [student['avg_quiz_score']],
                    'days_inactive': [student['days_inactive']],
                    'lessons_completed': [student['lessons_completed']]
                })
                
                prediction = int(model.predict(student_data)[0])
                probabilities = model.predict_proba(student_data)[0]
                
                risk_labels = {
                    0: 'Will Pass',
                    1: 'May Struggle',
                    2: 'Needs Help'
                }
                
                results.append({
                    'student_id': student.get('student_id'),
                    'risk_level': prediction,
                    'risk_label': risk_labels[prediction],
                    'confidence': float(probabilities[prediction]),
                    'probabilities': {
                        'will_pass': float(probabilities[0]),
                        'may_struggle': float(probabilities[1]),
                        'needs_help': float(probabilities[2])
                    }
                })
            except Exception as e:
                results.append({
                    'student_id': student.get('student_id'),
                    'error': str(e)
                })
        
        return jsonify({
            'success': True,
            'predictions': results
        })
        
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 400

@app.route('/health', methods=['GET'])
def health():
    """Health check endpoint"""
    return jsonify({
        'status': 'ok',
        'model_loaded': model is not None,
        'model_path': model_path,
        'model_exists': os.path.exists(model_path) if model_path else False
    })

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
