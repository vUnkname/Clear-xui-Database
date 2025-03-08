<div align="center"><img src="https://raw.githubusercontent.com/vUnkname/Clear-xui-Database/main/Clear-xui-Database.png" width="500">
<br>

برای توضیحات به <a href="https://github.com/vUnkname/Clear-xui-Database/blob/main/README-FA.md"> زبان فارسی، اینجا کلیک کنید</a>

</div>
<br><br>

# 🛠 Telegram Bot & Web Tool for Editing SQLite Database  

This project provides **two methods** for editing **SQLite database files** in **X-UI and 3X-UI panels**:  

1️⃣ **📲 Using the Telegram Bot:**  
   - Send your database file to **[the Telegram bot](https://t.me/ClearDataBase_bot)**.  
   - The bot checks if the **`settings` table** exists in the file.  
   - It updates the values of **`Certificate Public (Public Key Path)`, `Certificate Private (Private Key Path)`, `Web Listen (Listen IP)`, `Web Domain (Listen Domain)`** to an **empty string (`''`)**.  
   - The modified file is **sent back to you with a new name (including date and time) while keeping the original extension**.  

2️⃣ **🌐 Using the Web Tool on GitHub Pages:**  
   - Visit **[this link](https://vunkname.github.io/Clear-xui-Database)**.  
   - Upload your SQLite database file.  
   - The system **automatically modifies the specified fields**.  
   - Download the **modified file**.  

---  

## **📦 Features**  
✅ Supports **X-UI and 3X-UI** panel databases  
✅ Downloads the modified file with **the same extension and a timestamped name**  

---  

## **📜 Example**  

📌 **Input File:**  
`x-ui.db` (containing the `settings` table)  

📌 **Processing Result:**  
The modified file named **`20250308_120356.sqlite`** is sent back for download.  
