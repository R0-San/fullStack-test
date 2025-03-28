import React from "react";
import { useCart } from "./CartContext";
import PlaceOrderButton from './PlaceOrder.jsx';
import "../css/CartOverlay.css";

function CartOverlay({ isVisible, onClose}) {
  const { cartItems, updateQuantity} = useCart();

  const cartTotal = cartItems.reduce((acc, item) => {
    if (!item.prices || !item.prices[0] || !item.prices[0].amount) {
      console.error("Invalid price for item:", item);
      return acc;
    }
    return acc + item.quantity * item.prices[0].amount;
  }, 0);

  const handleOverlayClick = (e) => {
    if (!e.target.closest(".overlay-content") && !e.target.closest(".navigation-bar")) {
      onClose();
    }
  };

  return (
    <div className={`cart-overlay ${isVisible ? "open" : ""}`} onClick={handleOverlayClick}>
      <div className="overlay-content">
        <h2>My Bag: {cartItems.reduce((acc, item) => acc + item.quantity, 0)}</h2>
        {cartItems.length > 0 ? (
          <ul className="cart-items">
            {cartItems.map((item) => (
              <li key={item.key} className="cart-item">
                <div className="cart-item-details">
                  <p className="cart-item-name">{item.name}</p>
                  <p className="cart-item-price">
                    ${item.prices?.[0]?.amount.toFixed(2) || "N/A"}
                  </p>
                  {item.attributes?.map((attribute) => (
                    <div key={attribute.id} className="attribute">
                      <p>{attribute.name}:</p>
                      <div className="attribute-buttons" data-testid='cart-item-attribute-${attribute name in kebab case}'>
                        {attribute.items.map((itemOption) => (
                          <button
                            key={itemOption.id}
                            className={`attribute-btn ${item.selectedAttributes[attribute.id] === itemOption.value
                                ? "selected"
                                : ""
                              }`}
                            style={
                              attribute.name.toLowerCase() === "color"
                                ? {
                                  backgroundColor: itemOption.value,
                                  border:
                                    item.selectedAttributes[attribute.id] === itemOption.value
                                      ? "2px solid green"
                                      : "1px solid #000",
                                      width: "30px",
                                      height: "30px",
                                }
                                : {}
                            }
                            >
                              {attribute.name.toLowerCase() !== "color" && itemOption.displayValue}
                          </button>
                        ))}
                      </div>
                    </div>
                  ))}

                  <div className="cart-item-quantity-controls">
                    <button
                      className="cart-item-plus-btn"
                      onClick={() => updateQuantity(item.key, 1)}
                      data-testid='cart-item-amount-increase'
                    >
                      +
                    </button>
                    <span className="cart-item-quantity" data-testid='cart-item-amount'>{item.quantity}</span>
                    <button
                      className="cart-item-minus-btn"
                      onClick={() => updateQuantity(item.key, -1)}
                      data-testid='cart-item-amount-decrease'
                    >
                      -
                    </button>
                  </div>
                </div>

                <img src={item.gallery?.[0]?.url || "/image.png"} alt={item.name} className="cart-item-img" />
              </li>
            ))}
          </ul>
        ) : (
          <p>Your cart is empty.</p>
        )}

        <div className="cart-total" data-testid='cart-total'>
          <p>Total: ${cartTotal.toFixed(2)}</p>
        </div>

        <PlaceOrderButton />
      </div>
    </div>
  );
}

export default CartOverlay;