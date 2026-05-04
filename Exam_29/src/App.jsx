import { useEffect, useState } from 'react'

function formatTime(date) {
  const hours = String(date.getHours()).padStart(2, '0')
  const minutes = String(date.getMinutes()).padStart(2, '0')
  const seconds = String(date.getSeconds()).padStart(2, '0')

  return `${hours}:${minutes}:${seconds}`
}

function App() {
  const [currentTime, setCurrentTime] = useState(new Date())
  const [isRunning, setIsRunning] = useState(true)

  useEffect(() => {
    if (!isRunning) {
      return undefined
    }

    const timerId = setInterval(() => {
      setCurrentTime(new Date())
    }, 1000)

    return () => clearInterval(timerId)
  }, [isRunning])

  function toggleClock() {
    setIsRunning((previousState) => !previousState)
  }

  return (
    <div className="app">
      <div className="clock-card">
        <h1>Real-Time Digital Clock</h1>
        <p className="time">{formatTime(currentTime)}</p>
        <button type="button" onClick={toggleClock}>
          {isRunning ? 'Stop Clock' : 'Start Clock'}
        </button>
      </div>
    </div>
  )
}

export default App
