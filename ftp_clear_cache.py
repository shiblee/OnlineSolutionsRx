import ftplib

ftp = ftplib.FTP('sg2plzcpnl493881.prod.sin2.secureserver.net')
ftp.login('mjgykn3s9utp', 'Pk$%KB1mPQaNz%oG')
ftp.set_pasv(True)

def delete_dir_contents(path):
    try:
        ftp.cwd(path)
        for item in ftp.nlst():
            if item in ['.', '..']: continue
            try:
                ftp.delete(item)
            except:
                try:
                    delete_dir_contents(item)
                    ftp.cwd('..')
                    ftp.rmd(item)
                except:
                    pass
    except:
        pass

delete_dir_contents('public_html/storage_onlinesolutionsrx/cache')
ftp.quit()
print("Cache cleared")
