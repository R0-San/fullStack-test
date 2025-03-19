/*import React from 'react';
import { useMutation } from '@apollo/client';
import { CREATE_ORDER_MUTATION } from './graphql/mutations';

const App = () => {
  const [createOrder, { loading, error, data }] = useMutation(CREATE_ORDER_MUTATION);

  const handleCreateOrder = () => {
    createOrder({ variables: { cartItems: [] } });
  };

  return (
    <div>
      <button onClick={handleCreateOrder}>Create Order</button>
      {loading && <p>Loading...</p>}
      {error && <p>Error: {error.message}</p>}
      {data && <p>Order Created: {data.createOrder.id}</p>}
    </div>
  );
};
export default App;

{isCartOverlayVisible && <CartOverlay onClose={toggleCartOverlay} isVisible={isCartOverlayVisible} />}     

*/