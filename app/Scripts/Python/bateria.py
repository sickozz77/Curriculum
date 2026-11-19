import sys

def simular_bateria():
    # Pega o valor enviado pelo Laravel. Se não enviar nada, assume 100.
    try:
        bateria = int(sys.argv[1])
    except:
        bateria = 100

    while bateria > 0:
        print(f"Celular ligado. Bateria em {bateria}%")
        bateria -= 20
        
    print("O celular descarregou.")

if __name__ == "__main__":
    simular_bateria()