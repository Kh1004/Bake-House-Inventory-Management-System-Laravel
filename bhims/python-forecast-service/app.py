"""
Forecast Service (FastAPI) - Running Guide

Quick start (Windows, recommended)
1) Create venv and install deps
   python -m venv .venv
   .\.venv\Scripts\Activate.ps1
   pip install --upgrade pip
   pip install -r requirements.txt
   
   # Optional (for full ARIMA without fallbacks)
   pip install pandas==2.2.3 statsmodels==0.14.4

2) Run the API (port 8010)
   uvicorn app:app --host 127.0.0.1 --port 8010 --reload

3) Verify
   Open http://127.0.0.1:8010/health  → {"status":"ok"}

Laravel configuration
- In .env set:
  FORECAST_SERVICE_URL=http://127.0.0.1:8010
  FORECAST_SERVICE_TIMEOUT=20
- Then run:
  php artisan config:clear

Troubleshooting
- If you see 'optional-deps-missing' warning in responses, install:
  pip install pandas==2.2.3 statsmodels==0.14.4
- If NumPy import errors occur, reinstall cleanly:
  pip uninstall -y numpy && pip install --no-cache-dir numpy==2.1.3
- If port conflicts, change --port and update FORECAST_SERVICE_URL.
"""

from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, conlist
from typing import List, Optional
import json
import numpy as np


class ForecastRequest(BaseModel):
    series: conlist(float, min_length=1)
    dates: Optional[List[str]] = None
    steps: int = 7
    order: Optional[List[int]] = None  # [p, d, q]


class ForecastResponse(BaseModel):
    forecast: List[float]
    warning: Optional[str] = None


app = FastAPI(title="Forecast Service", version="1.0.0")


@app.get("/health")
def health() -> dict:
    return {"status": "ok"}


@app.post("/forecast", response_model=ForecastResponse)
def forecast(req: ForecastRequest):
    series = req.series
    dates = req.dates or []
    steps = max(1, int(req.steps))
    order = tuple(req.order) if req.order and len(req.order) == 3 else (1, 1, 1)

    # Minimum data guard
    min_needed = max(order[0] + order[2] + 1, 10)
    if len(series) < min_needed:
        mean = float(np.mean(series)) if series else 0.0
        return ForecastResponse(forecast=[max(0.0, mean) for _ in range(steps)])

    try:
        # Lazy import heavy optional dependencies
        try:
            import pandas as pd  # type: ignore
            from statsmodels.tsa.arima.model import ARIMA  # type: ignore
        except Exception as import_error:
            raise RuntimeError(f"optional-deps-missing: {import_error}")

        y = (
            pd.Series(series, index=pd.to_datetime(dates)) if dates and len(dates) == len(series)
            else pd.Series(series)
        )
        model = ARIMA(y, order=order, enforce_stationarity=False, enforce_invertibility=False)
        model_fit = model.fit()
        fc = model_fit.forecast(steps=steps)
        forecast_values = [max(0.0, float(v)) for v in fc.tolist()]
        return ForecastResponse(forecast=forecast_values)
    except Exception as e:
        mean = float(np.mean(series)) if series else 0.0
        fallback = [max(0.0, mean) for _ in range(steps)]
        return ForecastResponse(forecast=fallback, warning=str(e))


