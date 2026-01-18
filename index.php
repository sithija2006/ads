<?php
/* =========================
   API MODE (JSON RESPONSE)
   ========================= */
if (isset($_GET['api'])) {

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");

    $apiKey = "b377552d666849c7893077acfb8688b8";

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 9;
    $country = isset($_GET['country']) ? $_GET['country'] : "us";

    if ($country === "lk") {
        $url = "https://newsapi.org/v2/everything?q=sri+lanka&page=$page&pageSize=$pageSize&apiKey=$apiKey";
    } else {
        $url = "https://newsapi.org/v2/top-headlines?country=$country&page=$page&pageSize=$pageSize&apiKey=$apiKey";
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);

    if ($result === false) {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to fetch news"
        ]);
        exit;
    }

    curl_close($ch);
    echo $result;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>World News</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #f6f7fb;
  color: #111;
}
body.dark {
  background: #020617;
  color: #e5e7eb;
}
header {
  background: #111827;
  color: white;
  padding: 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
select, button {
  padding: 8px 10px;
  border-radius: 6px;
  border: none;
}
main {
  max-width: 1100px;
  margin: 20px auto;
  padding: 0 15px;
}
.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}
.card {
  background: white;
  border-radius: 10px;
  overflow: hidden;
}
body.dark .card {
  background: #111827;
}
.card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}
.card .content {
  padding: 12px;
}
.card h3 {
  font-size: 16px;
}
.card p {
  font-size: 14px;
}
.card a {
  display: inline-block;
  margin-top: 8px;
  background: #111827;
  color: white;
  padding: 6px 10px;
  text-decoration: none;
  border-radius: 5px;
}
#loader {
  text-align: center;
  padding: 20px;
}
@media (max-width: 800px) {
  .grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 500px) {
  .grid { grid-template-columns: 1fr; }
}
</style>
</head>

<body>

<header>
  <h2>World News</h2>
  <div>
    <select id="country">
      <option value="us">USA</option>
      <option value="in">India</option>
      <option value="gb">UK</option>
      <option value="au">Australia</option>
      <option value="lk">Sri Lanka</option>
    </select>
    <button id="darkBtn">Dark</button>
  </div>
</header>

<main>
  <div class="grid" id="news"></div>
  <div id="loader">Loading...</div>
</main>

<script>
let page = 1;
let loading = false;
let country = "us";

const news = document.getElementById("news");
const loader = document.getElementById("loader");

async function loadNews() {
  if (loading) return;
  loading = true;
  loader.style.display = "block";

  const res = await fetch(
    `index.php?api=1&page=${page}&pageSize=9&country=${country}`
  );

  const data = await res.json();

  loader.style.display = "none";
  loading = false;

  if (!data.articles) return;

  data.articles.forEach(a => {
    const div = document.createElement("div");
    div.className = "card";
    div.innerHTML = `
      <img src="${a.urlToImage || 'https://via.placeholder.com/400'}">
      <div class="content">
        <h3>${a.title}</h3>
        <p>${a.description || ""}</p>
        <a href="${a.url}" target="_blank">Read more</a>
      </div>
    `;
    news.appendChild(div);
  });

  page++;
}

window.addEventListener("scroll", () => {
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
    loadNews();
  }
});

document.getElementById("country").addEventListener("change", e => {
  country = e.target.value;
  page = 1;
  news.innerHTML = "";
  loadNews();
});

document.getElementById("darkBtn").addEventListener("click", () => {
  document.body.classList.toggle("dark");
});

loadNews();
</script>

</body>
</html>
