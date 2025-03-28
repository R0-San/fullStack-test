import React, { createContext, useContext, useState, useEffect } from "react";

const CartContext = createContext(null);
export const useCart = () => useContext(CartContext);

export const CartProvider = ({ children }) => {
  const [cartItems, setCartItems] = useState([]);

  useEffect(() => {
    const savedCart = localStorage.getItem("cart");
    if (savedCart) {
      try {
        setCartItems(JSON.parse(savedCart));
      } catch (error) {
        console.error("Failed to parse cart data from localStorage:", error);
        localStorage.removeItem("cart");
      }
    }
  }, []);

  const generateItemKey = (id, selectedAttributes) => {
    return `${id}-${Object.entries(selectedAttributes)
      .map(([key, value]) => `${key}-${value}`)
      .join("-")}`;
  };

  const addToCart = (product, selectedAttributes = {}) => {
    setCartItems((prevCart) => {
        const itemKey = generateItemKey(product.id, selectedAttributes);
        const existingItem = prevCart.find((item) => item.key === itemKey);

        let updatedCart;

        if (existingItem) {
            updatedCart = prevCart.map((item) =>
                item.key === itemKey ? { ...item, quantity: item.quantity + 1 } : item
            );
        } else {
            updatedCart = [
                ...prevCart,
                { ...product, selectedAttributes, key: itemKey, quantity: 1 },
            ];
        }

        localStorage.setItem("cart", JSON.stringify(updatedCart));
        return updatedCart;
    });
};

  const removeFromCart = (itemKey) => {
    const updatedCart = cartItems.filter((item) => item.key !== itemKey);
    setCartItems(updatedCart);
    localStorage.setItem("cart", JSON.stringify(updatedCart));
  };

  const updateQuantity = (itemKey, change) => {
    setCartItems((prevCart) => {
      const updatedCart = prevCart
        .map((item) => {
          if (item.key === itemKey) {
            const updatedQuantity = item.quantity + change;

            return updatedQuantity > 0 ? { ...item, quantity: updatedQuantity } : null;
          }
          return item;
        })
        .filter((item) => item !== null);

      localStorage.setItem("cart", JSON.stringify(updatedCart));
      return updatedCart;
    });
  };

  const updateCartItemAttributes = (itemKey, updatedAttributes) => {
    setCartItems((prevCart) => {
      const updatedCart = prevCart.map((item) => {
        if (item.key === itemKey) {
          return {
            ...item,
            selectedAttributes: {
              ...item.selectedAttributes,
              ...Object.fromEntries(
                Object.entries(updatedAttributes).map(([key, value]) => [parseInt(key, 10), value])
              ),
            },
          };
        }
        return item;
      });

      localStorage.setItem("cart", JSON.stringify(updatedCart));
      return updatedCart;
    });
  };

  const clearCart = () => {
    setCartItems([]);
    localStorage.removeItem("cart");
  };

  return (
    <CartContext.Provider value={{ cartItems, addToCart, removeFromCart, updateQuantity, updateCartItemAttributes, clearCart }}>
      {children}
    </CartContext.Provider>
  );
};