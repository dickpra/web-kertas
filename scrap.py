import mysql.connector
from bs4 import BeautifulSoup

# =========================
# KONFIGURASI DATABASE
# =========================
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': 'root',
    'database': 'db_kertas'
}

try:
    db = mysql.connector.connect(**db_config)
    cursor = db.cursor()
    print("Berhasil terhubung ke database!")
except Exception as e:
    print(f"Gagal terhubung ke database: {e}")
    exit()

# =========================
# BUAT TABEL JIKA BELUM ADA
# =========================
create_table_sql = """
CREATE TABLE IF NOT EXISTS stock_kertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jenis VARCHAR(50),
    gsm VARCHAR(50),
    lebar VARCHAR(50),
    no_roll VARCHAR(100) NOT NULL,
    no_roll_asli VARCHAR(100),
    sisa_kertas VARCHAR(50),
    no_po VARCHAR(100),
    wilayah VARCHAR(20),
    lokasi VARCHAR(50),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_no_roll (no_roll)
)
"""

cursor.execute(create_table_sql)
db.commit()

print("Tabel stock_kertas siap digunakan.")

# =========================
# BACA FILE HTML
# =========================
file_path = 'LapKStockKertasDet111.html'

with open(file_path, 'r', encoding='utf-8') as file:
    soup = BeautifulSoup(file, 'html.parser')

current_jenis = ""
current_gsm = ""
current_lebar = ""

rows = soup.find_all('tr')

data_berhasil = 0
data_update = 0

# =========================
# PROSES DATA
# =========================
for row in rows:
    cols = row.find_all('td')

    data = [
        col.text.strip()
        for col in cols
        if col.text.strip() != ''
    ]

    if not data:
        continue

    # Header kategori
    if len(data) == 3 and data[0] not in [
        'Jenis', 'Wil : B', 'No Dok', 'Halaman', 'Rev'
    ]:
        current_jenis = data[0]
        current_gsm = data[1]
        current_lebar = data[2]

    # Data detail
    elif len(data) >= 7 and current_jenis:

        no_roll = data[0]
        no_roll_asli = data[1]
        sisa_kertas = data[2]
        no_po = data[3]
        wilayah = data[-2]
        lokasi = data[-1]

        sql = """
        INSERT INTO stock_kertas
        (
            jenis,
            gsm,
            lebar,
            no_roll,
            no_roll_asli,
            sisa_kertas,
            no_po,
            wilayah,
            lokasi
        )
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)

        ON DUPLICATE KEY UPDATE
            jenis = VALUES(jenis),
            gsm = VALUES(gsm),
            lebar = VALUES(lebar),
            no_roll_asli = VALUES(no_roll_asli),
            sisa_kertas = VALUES(sisa_kertas),
            no_po = VALUES(no_po),
            wilayah = VALUES(wilayah),
            lokasi = VALUES(lokasi)
        """

        values = (
            current_jenis,
            current_gsm,
            current_lebar,
            no_roll,
            no_roll_asli,
            sisa_kertas,
            no_po,
            wilayah,
            lokasi
        )

        try:
            cursor.execute(sql, values)

            if cursor.rowcount == 1:
                data_berhasil += 1
            elif cursor.rowcount == 2:
                data_update += 1

        except Exception as e:
            print(f"Error data {no_roll}: {e}")

# =========================
# SIMPAN
# =========================
db.commit()

print(f"Insert baru : {data_berhasil}")
print(f"Update data : {data_update}")

cursor.close()
db.close()