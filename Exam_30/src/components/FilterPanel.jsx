import { useDispatch, useSelector } from "react-redux";
import {
  resetFilters,
  setCategoryFilter,
  setMaxPriceFilter
} from "../store/actions";

function FilterPanel() {
  const dispatch = useDispatch();
  const { products, filters } = useSelector((state) => state);

  const categories = ["All", ...new Set(products.map((product) => product.category))];

  const handleCategoryChange = (event) => {
    dispatch(setCategoryFilter(event.target.value));
  };

  const handlePriceChange = (event) => {
    dispatch(setMaxPriceFilter(event.target.value));
  };

  const handleReset = () => {
    dispatch(resetFilters());
  };

  return (
    <section className="panel">
      <h2>Filter Products</h2>

      <div className="field-group">
        <label htmlFor="category">Category</label>
        <select
          id="category"
          value={filters.category}
          onChange={handleCategoryChange}
        >
          {categories.map((category) => (
            <option key={category} value={category}>
              {category}
            </option>
          ))}
        </select>
      </div>

      <div className="field-group">
        <label htmlFor="price">Maximum Price</label>
        <input
          id="price"
          type="number"
          placeholder="Enter maximum price"
          value={filters.maxPrice}
          onChange={handlePriceChange}
          min="0"
        />
      </div>

      <div className="button-row">
        <button
          type="button"
          className="primary-btn"
          onClick={handleReset}
        >
          Reset Filters
        </button>
      </div>

      <div className="filter-summary">
        Filters are applied instantly using Redux state.
        <br />
        <strong>Selected Category:</strong> {filters.category}
        <br />
        <strong>Maximum Price:</strong> {filters.maxPrice || "No Limit"}
      </div>
    </section>
  );
}

export default FilterPanel;
