import {
  RESET_FILTERS,
  SET_CATEGORY_FILTER,
  SET_MAX_PRICE_FILTER
} from "./actionTypes";

export const setCategoryFilter = (category) => ({
  type: SET_CATEGORY_FILTER,
  payload: category
});

export const setMaxPriceFilter = (price) => ({
  type: SET_MAX_PRICE_FILTER,
  payload: price
});

export const resetFilters = () => ({
  type: RESET_FILTERS
});
