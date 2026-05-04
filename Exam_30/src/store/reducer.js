import products from "../data/products";
import {
  RESET_FILTERS,
  SET_CATEGORY_FILTER,
  SET_MAX_PRICE_FILTER
} from "./actionTypes";

const initialState = {
  products,
  filters: {
    category: "All",
    maxPrice: ""
  }
};

const productReducer = (state = initialState, action) => {
  switch (action.type) {
    case SET_CATEGORY_FILTER:
      return {
        ...state,
        filters: {
          ...state.filters,
          category: action.payload
        }
      };

    case SET_MAX_PRICE_FILTER:
      return {
        ...state,
        filters: {
          ...state.filters,
          maxPrice: action.payload
        }
      };

    case RESET_FILTERS:
      return {
        ...state,
        filters: {
          category: "All",
          maxPrice: ""
        }
      };

    default:
      return state;
  }
};

export default productReducer;
