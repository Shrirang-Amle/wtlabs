import { useState } from "react";
import { useDispatch } from "react-redux";
import NotificationList from "./components/NotificationList";
import { addNotification } from "./features/notifications/notificationSlice";

const notificationTemplates = {
  success: {
    title: "Success",
    message: "Your action was completed successfully.",
  },
  info: {
    title: "Information",
    message: "A new system update is available for review.",
  },
  warning: {
    title: "Warning",
    message: "Storage space is running low on this device.",
  },
};

function App() {
  const dispatch = useDispatch();
  const [selectedType, setSelectedType] = useState("success");

  const handleAddNotification = () => {
    dispatch(
      addNotification({
        ...notificationTemplates[selectedType],
        type: selectedType,
      })
    );
  };

  return (
    <main className="app-shell">
      <section className="hero-card">
        <p className="eyebrow">React + Redux Notification Center</p>
        <h1>System Notifications</h1>
        <p className="description">
          Add notifications to the Redux store and dismiss them directly from the UI.
        </p>

        <div className="controls">
          <label htmlFor="notification-type">Notification type</label>
          <select
            id="notification-type"
            value={selectedType}
            onChange={(event) => setSelectedType(event.target.value)}
          >
            <option value="success">Success</option>
            <option value="info">Info</option>
            <option value="warning">Warning</option>
          </select>

          <button type="button" onClick={handleAddNotification}>
            Add Notification
          </button>
        </div>
      </section>

      <section className="notifications-panel">
        <div className="panel-header">
          <h2>Active Notifications</h2>
          <p>Click dismiss to remove any item from the Redux state.</p>
        </div>
        <NotificationList />
      </section>
    </main>
  );
}

export default App;
