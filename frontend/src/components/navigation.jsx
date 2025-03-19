import { Link, NavLink } from "react-router-dom";
import { useCart } from "./CartContext";
import CartOverlay from "./CartOverlay";
import "../css/NavigationBar.css";
import "../css/CartOverlay.css";

function NavigationMenu({ toggleCartOverlay, isCartOverlayVisible }) {
  const { cartItems = [] } = useCart();

  if (!cartItems) {
    console.error("cartItems is undefined in NavigationMenu");
    return null;
  }

  const totalItems = cartItems.reduce((acc, item) => acc + item.quantity, 0);

  return (
    <>
      <div className="navigation-bar">
        <nav className="navbar">
          <ul>
            <li>
              <NavLink
                to="/"
                className={({ isActive }) => (isActive ? "nav-link active" : "nav-link")}
                data-testid={ ({ isActive }) => isActive ? 'active-category-link' : 'category-link' }
              >
                All
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/Clothes"
                className={({ isActive }) => (isActive ? "nav-link active" : "nav-link")}
                data-testid={ ({ isActive }) => isActive ? 'active-category-link' : 'category-link' }
              >
                Clothes
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/Tech"
                className={({ isActive }) => (isActive ? "nav-link active" : "nav-link")}
                data-testid={ ({ isActive }) => isActive ? 'active-category-link' : 'category-link' }
              >
                Technologies
              </NavLink>
            </li>
            <li className="logo">
              <Link to="/">
                <img src="/shop.png" alt="Shop Logo" />
              </Link>
            </li>
            <li className="cart-container">
              <button
                data-testid="cart-btn"
                onClick={toggleCartOverlay}
                className="cart-button"
              >
                <img src="../public/addcart.png" alt="Cart" />
                {totalItems > 0 && (
                  <span className="cart-item-count">{totalItems}</span>
                )}
              </button>
            </li>
          </ul>
        </nav>
      </div>
      {isCartOverlayVisible && (
        <CartOverlay onClose={toggleCartOverlay} isVisible={isCartOverlayVisible} />
      )}
    </>
  );
}

export default NavigationMenu;
