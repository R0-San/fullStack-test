import React, { useEffect } from 'react';
import { useQuery } from '@apollo/client';
import { Load_Products } from '../graphql/Queries.jsx';
import ProductCard from './product_card.jsx';
import { useParams } from 'react-router-dom';

function GetProducts({ addToCart }) {
  const { category } = useParams();
  const { error, loading, data } = useQuery(Load_Products);

  useEffect(() => {
    if (data) {
      console.log(data);
    }
  }, [data]);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error.message}</div>;

  const filteredProducts = category
    ? data.products.filter(product => 
        product.category.some(cat => cat.name.toLowerCase() === category.toLowerCase())
      )
    : data.products;

  return (
    <div className="product-container">
    <h1 className="category-heading">{category ? `Products in ${category} Category` : 'All Products'}</h1>

    <div className="product-grid">
      {filteredProducts.length > 0 ? (
        filteredProducts.map((product) => (
          <ProductCard key={product.id} product={product} addToCart={addToCart} />
        ))
      ) : (
        <p>No products found in this category.</p>
      )}
    </div>
  </div>
);
}

export default GetProducts;
