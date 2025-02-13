-- 1.Display total number of albums sold per artist
SELECT artist, SUM(sales_2022) AS total_albums_sold
FROM albums
GROUP BY artist
ORDER BY total_albums_sold DESC

-- 2.  Display combined album sales per artist
SELECT artist, COUNT(DISTINCT album) AS total_albums
FROM albums
GROUP BY artist
ORDER BY total_albums DESC

-- 3. Display the top 1 artist who sold most combined album sales
SELECT artist, SUM(sales_2022) AS total_album_sales
FROM albums
GROUP BY artist
ORDER BY total_album_sales DESC
LIMIT 1;

-- 4. Display the top 10 albums per year based on their number of sales
SELECT a.date_released, a.album, a.artist, a.sales_2022
FROM albums a
WHERE (
    SELECT COUNT(*) 
    FROM albums b
    WHERE YEAR(b.date_released) = YEAR(a.date_released)
    AND b.sales_2022 > a.sales_2022
) < 10
ORDER BY a.date_released DESC, a.sales_2022 DESC

-- 5.isplay list of albums based on the searched artist
SELECT * FROM albums
WHERE artist = 'Artist Name here'
ORDER BY date_released DESC

