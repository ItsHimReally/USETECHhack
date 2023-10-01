FROM python:latest

WORKDIR /search

COPY ./requirements.txt /search/requirements.txt

RUN pip install --no-cache-dir --upgrade -r /search/requirements.txt

COPY ./app /search/app

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "80"]

#HEALTHCHECK --interval=60s --timeout=10s --retries=3 CMD curl -sS 127.0.0.1:8000 || exit 1
