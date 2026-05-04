import { useDispatch, useSelector } from "react-redux";
import { removeNotification } from "../features/notifications/notificationSlice";

function NotificationList() {
  const dispatch = useDispatch();
  const notifications = useSelector((state) => state.notifications);

  if (notifications.length === 0) {
    return (
      <div className="empty-state">
        <p>No notifications right now.</p>
      </div>
    );
  }

  return (
    <div className="notification-list">
      {notifications.map((notification) => (
        <article
          key={notification.id}
          className={`notification-card notification-${notification.type}`}
        >
          <div>
            <h3>{notification.title}</h3>
            <p>{notification.message}</p>
          </div>
          <button
            type="button"
            className="dismiss-button"
            onClick={() => dispatch(removeNotification(notification.id))}
          >
            Dismiss
          </button>
        </article>
      ))}
    </div>
  );
}

export default NotificationList;
