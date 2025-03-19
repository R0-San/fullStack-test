import React, { useState } from "react";
import { ApolloProvider, ApolloClient, InMemoryCache, HttpLink, from } from "@apollo/client";
import { onError } from "@apollo/client/link/error";
import { Routes, Route } from 'react-router-dom';
import { CartProvider } from './components/CartContext';
import NavigationMenu from "./components/navigation.jsx";
import ProductDetailsPage from './pages/ProductDetailsPage';
import GetProducts from './components/GetProducts.jsx';
import "./css/App.css";

const errorLink = onError(({ graphQLErrors, networkError }) => {
  if (graphQLErrors) {
    graphQLErrors.forEach(({ message, locations, path }) => {
      console.error(`GraphQL error: ${message}, Location: ${locations}, Path: ${path}`);
    });
  }
  if (networkError) {
    console.error(`Network error: ${networkError.message}`);
    if (networkError.response) {
      console.error(`Network error response: ${JSON.stringify(networkError.response)}`);
    }
  }
});

const httpLink = new HttpLink({ uri: "http://testhost/GraphQL.php" });

const link = from([errorLink, httpLink]);

const client = new ApolloClient({
  cache: new InMemoryCache(),
  link: link,
  connectToDevTools: true,
});

function App() {

  const [isCartOverlayVisible, setCartOverlayVisible] = useState(false);

  const toggleCartOverlay = () => {
    setCartOverlayVisible((prev) => !prev);
  };

  return (
    <ApolloProvider client={client}>
      <CartProvider>
        <NavigationMenu toggleCartOverlay={toggleCartOverlay} isCartOverlayVisible={isCartOverlayVisible} />
        <main className="main-content">
          <Routes>
            <Route path="/" element={<GetProducts category="All" />} />
            <Route path="/:category" element={<GetProducts />} />
            <Route path="/products/:productId" element={<ProductDetailsPage />} />
          </Routes>
        </main>
      </CartProvider >
    </ApolloProvider>

  );
}


export default App;
