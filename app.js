let page = 1;
let country = "us";
let loading = false;

const news = document.getElementById("news");
const loader = document.getElementById("loader");

async function loadNews() {
  if (loading) return;
  loading = true;
  loader.style.display = "block";

  // Proxy URL (Cloudflare Worker)
  const url = `https://YOUR_WORKER_URL/?page=${page}&country=${country}`;

  const res = await fetch(url);
  const data = await res.json();

  loader.style.display = "none";
  loading = false;

  if (!data.articles) return;

  data.articles.forEach(a => {
    const card = document.createElement("div");
    card.className = "card";
    card.innerHTML = `
      <img src="${a.urlToImage || 'https://via.placeholder.com/400'}" />
      <div class="content">
        <h3>${a.title}</h3>
        <p>${a.description || ""}</p>
        <a href="${a.url}" target="_blank">Read more</a>
      </div>
    `;
    news.appendChild(card);
  });

  page++;
}

window.addEventListener("scroll", () => {
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
    loadNews();
  }
});

document.getElementById("country").addEventListener("change", (e) => {
  country = e.target.value;
  page = 1;
  news.innerHTML = "";
  loadNews();
});

document.getElementById("darkBtn").addEventListener("click", () => {
  document.body.classList.toggle("dark");
});

loadNews();


