import ftplib

try:
    ftp = ftplib.FTP('onlinesolutionsrx.com')
    ftp.login('onlinerx@misoftwaresolutions.com', 'Online@2026')
    ftp.set_pasv(False)
    print("Logged in successfully")
    print("PWD:", ftp.pwd())
    print("Files:", ftp.nlst())
    ftp.quit()
except Exception as e:
    print("Error:", e)
