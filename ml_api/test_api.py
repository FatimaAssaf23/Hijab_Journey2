"""
Simple test script to verify the ML API is working correctly.
Run this after starting the Flask API server.
"""

import requests
import json

API_BASE_URL = "http://localhost:5000"

def test_health():
    """Test the health endpoint"""
    print("Testing /health endpoint...")
    try:
        response = requests.get(f"{API_BASE_URL}/health")
        print(f"Status Code: {response.status_code}")
        print(f"Response: {json.dumps(response.json(), indent=2)}")
        return response.status_code == 200
    except Exception as e:
        print(f"❌ Error: {e}")
        return False

def test_predict():
    """Test the single prediction endpoint"""
    print("\nTesting /predict endpoint...")
    try:
        test_data = {
            "avg_watch_pct": 72.5,
            "completion_rate": 0.75,
            "avg_quiz_score": 65.0,
            "days_inactive": 6,
            "lessons_completed": 3
        }
        
        response = requests.post(
            f"{API_BASE_URL}/predict",
            json=test_data,
            headers={"Content-Type": "application/json"}
        )
        
        print(f"Status Code: {response.status_code}")
        result = response.json()
        print(f"Response: {json.dumps(result, indent=2)}")
        
        if result.get('success'):
            print(f"\n✅ Prediction: {result.get('risk_label')} (Risk Level: {result.get('risk_level')})")
            print(f"   Confidence: {result.get('confidence'):.1%}")
            return True
        else:
            print(f"❌ Error: {result.get('error')}")
            return False
            
    except Exception as e:
        print(f"❌ Error: {e}")
        return False

def test_batch_predict():
    """Test the batch prediction endpoint"""
    print("\nTesting /batch-predict endpoint...")
    try:
        test_data = {
            "students": [
                {
                    "student_id": 1,
                    "avg_watch_pct": 85.0,
                    "completion_rate": 0.9,
                    "avg_quiz_score": 80.0,
                    "days_inactive": 3,
                    "lessons_completed": 4
                },
                {
                    "student_id": 2,
                    "avg_watch_pct": 60.0,
                    "completion_rate": 0.6,
                    "avg_quiz_score": 55.0,
                    "days_inactive": 10,
                    "lessons_completed": 2
                },
                {
                    "student_id": 3,
                    "avg_watch_pct": 45.0,
                    "completion_rate": 0.4,
                    "avg_quiz_score": 40.0,
                    "days_inactive": 15,
                    "lessons_completed": 2
                }
            ]
        }
        
        response = requests.post(
            f"{API_BASE_URL}/batch-predict",
            json=test_data,
            headers={"Content-Type": "application/json"}
        )
        
        print(f"Status Code: {response.status_code}")
        result = response.json()
        print(f"Response: {json.dumps(result, indent=2)}")
        
        if result.get('success'):
            print(f"\n✅ Batch predictions completed for {len(result.get('predictions', []))} students")
            for pred in result.get('predictions', []):
                if 'error' not in pred:
                    print(f"   Student {pred.get('student_id')}: {pred.get('risk_label')} (Confidence: {pred.get('confidence'):.1%})")
                else:
                    print(f"   Student {pred.get('student_id')}: Error - {pred.get('error')}")
            return True
        else:
            print(f"❌ Error: {result.get('error')}")
            return False
            
    except Exception as e:
        print(f"❌ Error: {e}")
        return False

if __name__ == "__main__":
    print("=" * 70)
    print("ML API Test Suite")
    print("=" * 70)
    print(f"\nTesting API at: {API_BASE_URL}")
    print("Make sure the Flask API is running before running these tests!\n")
    
    results = []
    
    # Run tests
    results.append(("Health Check", test_health()))
    results.append(("Single Prediction", test_predict()))
    results.append(("Batch Prediction", test_batch_predict()))
    
    # Summary
    print("\n" + "=" * 70)
    print("Test Summary")
    print("=" * 70)
    for test_name, passed in results:
        status = "✅ PASSED" if passed else "❌ FAILED"
        print(f"{test_name:30} {status}")
    
    all_passed = all(result[1] for result in results)
    print("\n" + ("✅ All tests passed!" if all_passed else "❌ Some tests failed"))
