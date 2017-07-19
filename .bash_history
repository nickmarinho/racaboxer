


#1296044362
ll
#1296044365
cd www/
#1296044365
ll
#1296044368
nano teste.php 
#1296044582
nano ../application/configs/application.ini 
#1296044674
nano teste.php 
#1296044696
nano ../application/configs/application.ini 
#1296044911
nano ../application/views/scripts/blog/comments.php 
#1296044957
nano index.php 
#1296045169
nano ../application/views/scripts/blog/comments.php 
#1296045187
nano ../application/views/scripts/blog/comments.php 
#1296045250
fgrep -ir "public" . |more
#1296045260
fgrep -r "public" . |more
#1296045297
fgrep -r "public/" . |more
#1296045307
ll
#1296045319
rm teste.php 
#1296045322
ll
#1296045348
mv index.html logo.jpg img-bkp/
#1296045349
ll
#1296045411
cd phpmyadmin/
#1296045416
nano config.inc.php 
#1296045549
cd ..
#1296045554
cd ..
#1296045557
cd application/
#1296045558
ll
#1296045567
nano controllers/IndexController.php 
#1296045958
nano views/scripts/blog/comments.php 
#1296045968
cd ..
#1296045975
ll
#1296045988
nano application/views/scripts/index/index.phtml 
#1296046215
nano application/layouts/scripts/frontend/topnavbar.phtml 
#1296046285
nano application/models/ImagesCat.php 
#1296046360
nano application/views/scripts/images/common.phtml 
#1296047267
ll
#1296047318
nano racaboxer.sql 
#1296047355
mysql -h mysql.racaboxer.com.br -uroot -p --database=racaboxer < racaboxer.sql 
#1296047372
mysql -h mysql.racaboxer.com.br -uracaboxer -p --database=racaboxer < racaboxer.sql 
#1296047385
mysql -h mysql.racaboxer.com.br -uracaboxer -p
#1296056542
ll
#1296056550
nano application/views/scripts/livro/index.phtml 
#1296056601
nano application/views/scripts/livro/index.phtml 
#1296057089
cat www/index.php 
#1296057128
nano www/index.php 
#1296057249
nano www/index.php 
#1296057347
nano application/controllers/ImagesController.php 
#1296057380
nano application/views/scripts/admin/blogadd.phtml 
#1296057391
nano application/views/scripts/admin/blogedit.phtml 
#1296057407
nano application/views/scripts/admin/senderemail.phtml 
#1296057427
nano application/views/scripts/blog/comments.php 
#1296057439
nano application/views/scripts/index/index.phtml 
#1296057451
nano application/views/scripts/index/mail.phtml 
#1296057471
nano application/views/scripts/livro/index.phtml 
#1296057556
nano www/index.php 
#1296058040
nano application/views/scripts/admin/page*
#1296058116
nano application/views/scripts/admin/pageedit.phtml 
#1296058809
nano application/views/scripts/images/enviadas.phtml 
#1296058813
nano application/views/scripts/images/common.phtml 
#1296058888
nano application/views/scripts/images/common.phtml 
#1296059041
nano application/controllers/RssController.php 
#1296131217
nano www/index.php 
#1297085321
ll
#1297085339
nano application/controllers/IndexController.php 
#1297094643
ll
#1297094647
nano application/controllers/IndexController.php 
#1297094694
nano application/controllers/AdminController.php 
#1301319417
ll
#1301319448
nano application/layouts/scripts/frontend/footer.phtml 
#1319086818
ll
#1319086823
cd library/
#1319086824
ls
#1319086825
ll
#1319086829
cd Zend/
#1319086830
ll
#1319086834
rm * -R
#1319086839
ll
#1319086901
cd ..
#1319086903
rm Zend/
#1319086907
rmdir Zend/
#1319086908
ll
#1319087411
ll
#1319087415
unzip Zend.zip 
#1319087420
ll
#1319087425
rm Zend.zip 
#1319087540
cd ..
#1319087541
ll
#1319087548
rm application/ -Rf
#1319087560
ll
#1319087567
unzip application.zip 
#1319087570
ll
#1319087580
nano application/configs/application.ini 
#1319087599
nano www/index.bkp 
#1319087606
pwd
#1319087612
nano www/index.bkp 
#1319087959
ll
#1319087961
cd sql/
#1319087963
ll
#1319087982
mysql -hmysql.racaboxer.com.br -uracaboxer -pnick1978
#1319088000
ll
#1319088011
mysql -hmysql.racaboxer.com.br -uracaboxer -pnick1978 < rb.sql 
#1319088021
mysql -hmysql.racaboxer.com.br -uracaboxer -pnick1978 racaboxer < rb.sql 
#1340976462
ll
#1340976469
cd www/
#1340976470
ll
#1380043024
git
#1380043040
git init
#1380043044
git add .
#1408999157
ll
#1408999161
cd sql/
#1408999161
ll
#1408999177
nano dump-online-to-local-full.bat 
#1408999196
mysqldump --default-character-set=utf8 -hmysql.racaboxer.com.br -uracaboxer -pnick1978 racaboxer > rb.sql
#1408999198
ll
#1408999201
ls -lh
#1408999206
nano rb.sql 
#1408999252
ll
#1408999259
bzip2 rb.sql 
#1408999260
ll
#1408999273
mv rb.sql.bz2 ../www/
#1408999314
rm ../www/rb.sql.bz2 
#1408999315
ll
