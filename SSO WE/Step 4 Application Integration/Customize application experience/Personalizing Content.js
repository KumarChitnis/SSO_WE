const userData = await fetchUserData();

if (userData) {
  const userInterests = userData.interests;
  const recommendedProducts = await fetchRecommendedProducts(userInterests);

  // Display recommended products
  const productContainer = document.getElementById('product-container');
  recommendedProducts.forEach((product) => {
    const productCard = document.createElement('div');
    productCard.innerHTML = `
      <h2>${product.name}</h2>
      <p>${product.description}</p>
      <button>Buy Now</button>
    `;
    productContainer.appendChild(productCard);
  });
}