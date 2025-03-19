import { gql } from '@apollo/client';

export const Load_Products = gql`
  query LoadProducts {
    products {
      id
      product_id
      name
      inStock
      gallery {
        id
        url
      }
      description
      category {
        id
        name
      }
      attributes {
        id
        name
        type
        items {
          id
          displayValue
          value
          item_id
        }
      }
      prices {
        id
        amount
        currency {
          id
          label
          symbol
        }
      }
      brand
    }
  }
`;

export const Load_Product_Details = gql`
  query GetProductDetails($id: ID!) {
    product(id: $id) {
      id
      product_id
      name
      inStock
      gallery {
        id
        url
      }
      description
      category {
        id
        name
      }
      attributes {
        id
        name
        type
        items {
          id
          displayValue
          value
          item_id
        }
      }
      prices {
        id
        amount
        currency {
          id
          label
          symbol
        }
      }
      brand
    }
  }
`;