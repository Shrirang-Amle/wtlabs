import { useDispatch, useSelector } from "react-redux";
import {
  resetFilters,
  setCategoryFilter,
  setMaxPriceFilter
} from "./store/actions";

function App() {
  const dispatch = useDispatch();
  const { products, filters } = useSelector((state) => state);

  const categories = ["All", ...new Set(products.map((product) => product.category))];

  const filteredProducts = products.filter((product) => {
    const categoryMatch =
      filters.category === "All" || product.category === filters.category;

    const priceMatch =
      filters.maxPrice === "" || product.price <= Number(filters.maxPrice);

    return categoryMatch && priceMatch;
  });

  return (
    <div className="container">
      <h1>Product Filter App Using Redux</h1>

      <h2>Filter Products</h2>

      <label htmlFor="category">Select Category</label>
      <select
        id="category"
        value={filters.category}
        onChange={(event) => dispatch(setCategoryFilter(event.target.value))}
      >
        {categories.map((category) => (
          <option key={category} value={category}>
            {category}
          </option>
        ))}
      </select>

      <label htmlFor="price">Enter Maximum Price</label>
      <input
        id="price"
        type="number"
        placeholder="Enter price"
        value={filters.maxPrice}
        onChange={(event) => dispatch(setMaxPriceFilter(event.target.value))}
      />

      <button type="button" onClick={() => dispatch(resetFilters())}>
        Reset Filters
      </button>

      <h2>Filtered Products</h2>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
          </tr>
        </thead>
        <tbody>
          {filteredProducts.length > 0 ? (
            filteredProducts.map((product) => (
              <tr key={product.id}>
                <td>{product.id}</td>
                <td>{product.name}</td>
                <td>{product.category}</td>
                <td>Rs. {product.price}</td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="4">No products found</td>
            </tr>
          )}
        </tbody>
      </table>
    </div>
  );
}

export default App;
