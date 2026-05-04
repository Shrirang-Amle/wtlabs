import { createSlice } from "@reduxjs/toolkit";

const initialState = [
  {
    id: 1,
    title: "Welcome",
    message: "Notifications are now managed by Redux.",
    type: "success",
  },
];

const notificationSlice = createSlice({
  name: "notifications",
  initialState,
  reducers: {
    addNotification: (state, action) => {
      state.unshift({
        id: Date.now(),
        ...action.payload,
      });
    },
    removeNotification: (state, action) =>
      state.filter((notification) => notification.id !== action.payload),
  },
});

export const { addNotification, removeNotification } = notificationSlice.actions;
export default notificationSlice.reducer;
