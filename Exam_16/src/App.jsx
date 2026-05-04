import { useState } from "react";

const EXCHANGE_RATE = 83.5;

export default function App() {
  const [dollars, setDollars] = useState("");
  const [rupees, setRupees] = useState("");

  const handleInputChange = (event) => {
    setDollars(event.target.value);
  };

  const convertCurrency = () => {
    const amount = Number(dollars);

    if (!dollars || Number.isNaN(amount) || amount < 0) {
      setRupees("Please enter a valid dollar amount.");
      return;
    }

    setRupees(`Rs. ${(amount * EXCHANGE_RATE).toFixed(2)}`);
  };

  const resetFields = () => {
    setDollars("");
    setRupees("");
  };

  return (
    <main className="page">
      <section className="converter-card">
        <p className="eyebrow">ReactJS Currency Converter</p>
        <h1>Dollar to Rupee Converter</h1>
        <p className="description">
          Enter an amount in US dollars and convert it into Indian rupees using
          React state and event handlers.
        </p>

        <label htmlFor="dollar-input" className="label">
          Amount in Dollars ($)
        </label>
        <input
          id="dollar-input"
          className="input"
          type="number"
          min="0"
          step="0.01"
          value={dollars}
          onChange={handleInputChange}
          placeholder="Enter amount in dollars"
        />

        <div className="button-row">
          <button className="primary-button" onClick={convertCurrency}>
            Convert to Rupees
          </button>
          <button className="secondary-button" onClick={resetFields}>
            Reset
          </button>
        </div>

        {rupees && <p className="result">{rupees}</p>}
        <p className="rate">Current conversion used: 1 USD = Rs. {EXCHANGE_RATE}</p>
      </section>
    </main>
  );
}
