let page = 1;
const pageSize = 9;
let country = "us";
let loading = false;

const newsContainer = document.getElementById("news-container");
const loader = document.getElementById("loader");
const countrySelect = document.getElementById("country-select");
const darkToggle = document.getElementById("dark-toggle");

async function fetchNews() {
  if (loading) return;
  loading = true;
  loader.style.display = "block";

  const response = await fetch(
    `news.php?page=${page}&pageSize=${pageSize}&country=${country}`
  );

  const data = await response.json();

  loader.style.display = "none";
  loading = false;

  return data.articles || [];
}

function renderNews(articles) {
  articles.forEach(article => {
    const div = document.createElement("div");
    div.className = "news-item";

    div.innerHTML = `
      <img src="${article.urlToImage || 'https://via.placeholder.com/400'}">
      <div class="news-content">
        <h3>${article.title}</h3>
        <p>${article.description || ""}</p>
        <a href="${article.url}" target="_blank">Read more</a>
      </div>
    `;

    newsContainer.appendChild(div);
  });
}

async function loadMore() {
  const articles = await fetchNews();
  renderNews(articles);
  page++;
}

window.addEventListener("scroll", () => {
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
    loadMore();
  }
});

countrySelect.addEventListener("change", () => {
  country = countrySelect.value;
  page = 1;
  newsContainer.innerHTML = "";
  loadMore();
});

darkToggle.addEventListener("click", () => {
  document.body.classList.toggle("dark");
});

loadMore();
