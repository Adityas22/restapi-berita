# REST API Berita – Laravel

REST API ini digunakan untuk manajemen **Authentication**, **Post**, dan **Comment**.  
Dokumentasi ini dilengkapi dengan contoh request menggunakan Postman beserta screenshot.

---

## 🚀 Fitur Utama
- 🔐 Authentication (Register, Login, me, Logout)
- 📰 Post (List, Detail, Create, Update, Delete)
- 💬 Comment (Create, Update, Delete)

---

# 🔐 AUTHENTICATION

## 1. Register  
**POST** `/register`  
![Register](ss/image.png)

---

## 2. Login  
**POST** `/login`  
![Login](ss/auth_login.png)

---

## 3. Logout  
**GET** `/logout`  
Header: `Authorization: Bearer <token>`  
![Logout](ss/auth_logout.png)

---

# 📰 POST

## 1. Get All Posts  
**GET** `/posts`  
![Get Posts](ss/post_berita.png)

---


## 2. Create Post  
**POST** `/posts`  
Body (multipart form-data):  
- title  
- content  
- image *(optional)*  
![Create Post](ss/post_berita_add.png)

---

# 💬 COMMENT

## 1. Create Comment  
**POST** `/comments`  
![Create Comment](ss/post_koment.png)

---


## 2. Delete Comment  
**DELETE** `/comments/{id}`  
![Delete Comment](ss/post_koment_delete.png)

---

# 📝 Catatan
- Semua endpoint yang membutuhkan login wajib menggunakan header. Butki diatas hanya beberapa karena seringnya melakukan pembaruan setiap melakukan endpoint

---

## 📌 Tampilan Frontend

| Halaman | Screenshot |
|--------|------------|
| Home | ![Home](ss/webhome.png) |
| Detail Berita | ![Detail](ss/webdetail.png) |
| Komentar | ![Komentar](ss/webkomentar.png) |
| Login | ![Login](ss/weblogin.png) |
| Register | ![Register](ss/webregister.png) |

---
## 📝 Catatan
- Tabel di atas hanya menampilkan beberapa tampilan FE. Pada akses Home bisa dilihat tanpa harus login tapi masih belom bisa berkomentar seperti anonymous
- Komentar bisa edit hapus cuma kalau login sesuai users ID yang sama
