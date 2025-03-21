import React from "react";
import { useCart } from "./CartContext";
import { useMutation } from "@apollo/client";
import { CREATE_ORDER_MUTATION } from '../graphql/Mutations';
import "../css/CartOverlay.css";

function PlaceOrderButton() {
  const { cartItems, clearCart } = useCart();
  const [createOrder, { loading, error }] = useMutation(CREATE_ORDER_MUTATION);

  const handlePlaceOrder = async () => {
    try {
      const response = await createOrder({
        variables: {
          items: cartItems.map((item) => ({
            productId: item.id,
            quantity: item.quantity,
            selectedAttributes: Object.entries(item.selectedAttributes).map(([attributeId, value]) => ({
              attributeId: parseInt(attributeId, 10),
              value,
            })),
          })),
        },
      });
  
      console.log("Response from backend:", response);
      clearCart();
    } catch (err) {
      console.error("Error placing order:", err);
      alert("Failed to place order. Please try again.");
    }
  };

  if (loading) return <button disabled>Placing Order...</button>;
  if (error) return <p>Error: {error.message}</p>;

  return (
    <button onClick={handlePlaceOrder} disabled={cartItems.length === 0} className="place-order-btn">
      Place Order
    </button>
  );
}

export default PlaceOrderButton;
