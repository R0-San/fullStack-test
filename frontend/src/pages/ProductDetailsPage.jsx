import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/client';
import { useCart } from "../components/CartContext";
import { Load_Products } from '../graphql/Queries';
import "../css/ProductDetails.css";

function ProductDetailsPage() {
    const { productId } = useParams();
    const { addToCart } = useCart();
    const { data, loading, error } = useQuery(Load_Products);
    const [selectedAttributes, setSelectedAttributes] = useState({});
    const [currentImageIndex, setCurrentImageIndex] = useState(0);

    useEffect(() => {
        if (data) {
            console.log("Fetched products:", data.products);
        }
    }, [data]);

    if (loading) return <div className="loading">Loading...</div>;
    if (error) return <div className="error">Error: {error.message}</div>;

    const product = data?.products.find((p) => String(p.id) === String(productId));

    if (!product) {
        console.error("Product not found. Available IDs:", data.products.map(p => p.id));
        return <div className="error">Product not found</div>;
    }

    const prevImage = () => {
        setCurrentImageIndex((prevIndex) =>
            prevIndex === 0 ? product.gallery.length - 1 : prevIndex - 1
        );
    };

    const nextImage = () => {
        setCurrentImageIndex((prevIndex) =>
            prevIndex === product.gallery.length - 1 ? 0 : prevIndex + 1
        );
    };

    const handleAttributeSelect = (attributeId, value) => {
        setSelectedAttributes((prev) => ({
            ...prev,
            [attributeId]: value,
        }));
    };

    return (
        <div className="product-details-container">
            <div className="product-gallery" data-testid='product-gallery'>
                <button className="arrow left-arrow" onClick={prevImage}>&#9664;</button>

                <div className="main-image">
                    <img src={product.gallery[currentImageIndex]?.url} alt={product.name} />
                </div>

                <button className="arrow right-arrow" onClick={nextImage}>&#9654;</button>

                <div className="small-gallery">
                    {product.gallery.map((image, index) => (
                        <img
                            key={index}
                            src={image.url}
                            alt={`Gallery Image ${index + 1}`}
                            className={index === currentImageIndex ? "active" : ""}
                            onClick={() => setCurrentImageIndex(index)}
                        />
                    ))}
                </div>
            </div>

            <div className="product-info">
                <h1 className="product-name">{product.name}</h1>
                <p className="product-brand-label">Brand: </p>
                <p className="product-brand">{product.brand}</p>

                <div className="attribute-options" data-testid='product-attribute-${attribute in kebab case}'>
                    {product.attributes.map((attribute) => (
                        <div key={attribute.id} className="attribute">
                            <label>{attribute.name}</label>
                            <div className="attribute-buttons">
                                {attribute.items.map((item) => (
                                    <button
                                        key={item.id}
                                        className={`attribute-btn ${selectedAttributes[attribute.id] === item.value ? 'selected' : ''}`}
                                        onClick={() => handleAttributeSelect(attribute.id, item.value)}
                                        style={
                                            attribute.name.toLowerCase() === "color"
                                                ? { backgroundColor: item.value, border: selectedAttributes[attribute.id] === item.value ? "5px solid rgb(139, 212, 122)" : "1px solid #000" }
                                                : {}
                                        }
                                        disabled={!product.inStock}
                                    >
                                        {attribute.name.toLowerCase() !== "color" && item.displayValue}
                                    </button>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>

                <p className="product-price">
                    {product.prices[0]?.amount} {product.prices[0]?.currency.symbol}
                </p>

                <button className="add-to-cart-btn" onClick={() => addToCart(product, selectedAttributes)} disabled={!product.inStock} data-testid='add-to-cart'>
                    Add to Cart
                </button>

                <div className="product-description">
                    <div>{product.description}</div>
                </div>
            </div>
        </div>
    );
}

export default ProductDetailsPage;
