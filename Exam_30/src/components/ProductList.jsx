import { useSelector } from "react-redux";

function ProductList() {
  const { products, filters } = useSelector((state) => state);

  const filteredProducts = products.filter((product) => {
    const categoryMatch =
      filters.category === "All" || product.category === filters.category;

    const priceMatch =
      filters.maxPrice === "" || product.price <= Number(filters.maxPrice);

    return categoryMatch && priceMatch;
  });

  return (
    <section className="products-section">
      <div className="products-header">
        <div>
          <h2>Available Products</h2>
          <p className="product-count">
            Showing {filteredProducts.length} of {products.length} products
          </p>
        </div>
      </div>

      {filteredProducts.length > 0 ? (
        <div className="product-grid">
          {filteredProducts.map((product) => (
            <article key={product.id} className="product-card">
              <h3>{product.name}</h3>
              <p>
                <strong>Category:</strong> {product.category}
              </p>
              <p className="price-tag">Price: Rs. {product.price}</p>
            </article>
          ))}
        </div>
      ) : (
        <div className="empty-state">
          No products match the selected filters.
        </div>
      )}
    </section>
  );
}

export default ProductList;
