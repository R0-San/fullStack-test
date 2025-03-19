import { gql } from '@apollo/client';

export const CREATE_ORDER_MUTATION = gql`
  mutation CreateOrder($items: [OrderItemInput]!) {
    placeOrder(items: $items) {
      id
      totalAmount
      status
      createdAt
      items {
        productId
        quantity
        selectedAttributes {
          attributeId
          value
        }
      }
    }
  }
`;
