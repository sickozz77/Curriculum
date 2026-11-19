
import pandas as pd
import json
from datetime import datetime, timedelta
from influxdb_client import InfluxDBClient

# CONFIGURAÇÕES DO INFLUXDB
URL_INFLUX = "https://telemetria.indflow.com.br:8086"
TOKEN = "HFwsUIIWPHAWwDJKIK5kh8Z-bDAxEazIoDL1E0CCZtKoHkiExidGtAdbheCVOW5Lsco_OdLOv9P8Dpv6O7Z_dg=="
ORG = "indflow"
BUCKET = "TelemAPI"

# CONSULTA
query = f"""
from(bucket: \"{BUCKET}\")
  |> range(start: -24h)
  |> filter(fn: (r) => r["_measurement"] == "ChirpStack_Params")
  |> filter(fn: (r) => r["_field"] == "mainVar")
  |> filter(fn: (r) => r["_applicationName"] == "APLIFLOW")
  |> filter(fn: (r) => r["_deviceName"] == "eui-00051680001450b8")
  |> keep(columns: ["_time", "_value"])
"""

client = InfluxDBClient(url=URL_INFLUX, token=TOKEN, org=ORG)
df = client.query_api().query_data_frame(query)
client.close()

if df.empty:
    print("❌ Nenhum dado retornado do InfluxDB.")
    exit()

df = df[["_time", "_value"]].rename(columns={"_time": "_time", "_value": "_value"})
df["_time"] = pd.to_datetime(df["_time"])
df.sort_values(by="_time", inplace=True)

# PROCESSAMENTO PARA FORMATO ANA
expanded = []
for i in range(1, len(df)):
    t0 = df.iloc[i-1]["_time"]
    t1 = df.iloc[i]["_time"]
    v0 = df.iloc[i-1]["_value"]
    v1 = df.iloc[i]["_value"]

    minutos = int((t1 - t0).total_seconds() / 60)
    delta_total = v1 - v0

    if minutos <= 0:
        continue

    if delta_total == 0:
        for m in range(1, minutos + 1):
            expanded.append({
                "timestamp": (t0 + timedelta(minutes=m)).strftime('%Y-%m-%dT%H:%M:%S-03:00'),
                "volume": int(v0),
                "duracao": 0
            })
    else:
        incremento_inteiro = delta_total // minutos
        restante = delta_total % minutos
        valor = v0
        for m in range(1, minutos + 1):
            valor += incremento_inteiro
            if m == minutos:
                valor += restante
            expanded.append({
                "timestamp": (t0 + timedelta(minutes=m)).strftime('%Y-%m-%dT%H:%M:%S-03:00'),
                "volume": int(valor),
                "duracao": 60
            })

df_exp = pd.DataFrame(expanded)
df_exp['timestamp'] = pd.to_datetime(df_exp['timestamp'])
df_15min = df_exp.resample('15min', on='timestamp').agg({
    'volume': 'last',
    'duracao': 'sum'
}).reset_index()

df_15min['volume_anterior'] = df_15min['volume'].shift(1)
df_15min['delta_volume'] = df_15min['volume'] - df_15min['volume_anterior']
df_15min['vazao'] = df_15min.apply(lambda x: (x['delta_volume'] / x['duracao']) if x['duracao'] > 0 else 0, axis=1)

leituras = []
for _, row in df_15min.iterrows():
    leituras.append({
        "horario": row["timestamp"].strftime('%Y-%m-%dT%H:%M:%S-03:00'),
        "vazao": round(row["vazao"], 2),
        "duracao": int(row["duracao"]),
        "volume": int(row["volume"])
    })

saida_json = {
    "intervencao": "DCE_001",
    "leituras": leituras
}

# SALVAR JSON LOCALMENTE
CAMINHO_SAIDA = "saida_bomba.json"
with open(CAMINHO_SAIDA, "w") as f:
    json.dump(saida_json, f, indent=4)

print(f"✅ JSON salvo localmente em: {CAMINHO_SAIDA}")
