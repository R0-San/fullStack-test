import React from 'react';
import { useNavigate } from 'react-router-dom';
import "../css/ProductCard.css";
import { useCart } from "./CartContext";

function ProductCard({ product }) {
    const { addToCart } = useCart();
    const navigate = useNavigate();

    const handleAddToCart = (e) => {
        e.stopPropagation();
        addToCart(product);
    };


    const imageUrl = product.gallery?.[0]?.url || '/image.png';


    const goToProductDetail = () => {
        navigate(`/products/${product.id}`);
    };

    return (
        <div className={`product-card ${product.inStock === false ? "out-of-stock" : ""}`} onClick={goToProductDetail} data-testid='product-${product name in kebab case}'>
            <div className="product-image" onClick={goToProductDetail}>
                <img src={imageUrl} alt={product.name} />
                {!product.inStock && <div className="out-of-stock-overlay">OUT OF STOCK</div>}
            </div>
            <div>
                <button className="add-btn" onClick={handleAddToCart} disabled={!product.inStock}>
                    <img className="btn-img" src="../public/cart.png" alt="Add to Cart" />
                </button>
            </div>
            <div className="product-info">
                <p className="product-info-name">{product.name}</p>
                <p className="product-info-price">
                    {product.prices?.[0]?.amount || 'N/A'} {product.prices?.[0]?.currency?.symbol || 'USD'}
                </p>
            </div>
        </div>
    );
}

export default ProductCard;
