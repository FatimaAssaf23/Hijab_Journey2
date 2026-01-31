"""
Create a simple mock model for testing purposes.
This is a basic model that will work but won't be accurate.
For production, you should train a proper model with real data.
"""

import joblib
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split

print("Creating mock student risk prediction model...")

# Generate synthetic training data
np.random.seed(42)
n_samples = 1000

# Generate features
avg_watch_pct = np.random.uniform(0, 100, n_samples)
completion_rate = np.random.uniform(0, 1, n_samples)
avg_quiz_score = np.random.uniform(0, 100, n_samples)
days_inactive = np.random.randint(0, 30, n_samples)
lessons_completed = np.random.randint(0, 20, n_samples)

# Create feature matrix
X = np.column_stack([
    avg_watch_pct,
    completion_rate,
    avg_quiz_score,
    days_inactive,
    lessons_completed
])

# Generate labels based on simple rules (for mock model)
# 0 = Will Pass (good performance)
# 1 = May Struggle (medium performance)
# 2 = Needs Help (poor performance)
y = np.zeros(n_samples, dtype=int)

for i in range(n_samples):
    score = (avg_watch_pct[i] * 0.3 + 
             completion_rate[i] * 100 * 0.3 + 
             avg_quiz_score[i] * 0.3 - 
             days_inactive[i] * 2)
    
    if score >= 60:
        y[i] = 0  # Will Pass
    elif score >= 30:
        y[i] = 1  # May Struggle
    else:
        y[i] = 2  # Needs Help

# Split data
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train a simple Random Forest model
print("Training model...")
model = RandomForestClassifier(n_estimators=50, random_state=42, max_depth=10)
model.fit(X_train, y_train)

# Evaluate
train_score = model.score(X_train, y_train)
test_score = model.score(X_test, y_test)
print(f"Training accuracy: {train_score:.2%}")
print(f"Test accuracy: {test_score:.2%}")

# Save model
model_path = 'student_risk_model.pkl'
joblib.dump(model, model_path)
print(f"\n[OK] Mock model saved to {model_path}")
print("\n[WARNING] NOTE: This is a MOCK model for testing only!")
print("   For production, train a model with real student data.")
