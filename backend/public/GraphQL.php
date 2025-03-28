<?php

namespace App\Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../vendor/autoload.php';
require_once(__DIR__ . "/../config/config.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Resolvers\CategoryResolver;
use App\GraphQL\Resolvers\GalleryResolver;
use App\GraphQL\Resolvers\ItemResolver;
use App\GraphQL\Resolvers\PriceResolver;
use App\GraphQL\Resolvers\AttributeResolver;
use App\GraphQL\Resolvers\ProductsResolver;
use App\GraphQL\Resolvers\OrderResolver;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Throwable;

class GraphQL
{
    static public function handle()
    {
        global $conn;

        if ($conn->connect_error) {
            error_log("Database connection failed: " . $conn->connect_error);
            die("❌ Database connection failed.");
        }

        try {
            $categoryResolver = new CategoryResolver($conn);
            $galleryResolver = new GalleryResolver();
            $itemResolver = new ItemResolver();
            $priceResolver = new PriceResolver();
            $attributeResolver = new AttributeResolver();
            $productResolver = new ProductsResolver();
            $orderResolver = new OrderResolver($conn);

            $categoryType = new ObjectType([
                'name' => 'Category',
                'fields' => [
                    'id' => Type::int(),
                    'name' => Type::string(),
                ],
            ]);

            $galleryType = new ObjectType([
                'name' => 'Gallery',
                'fields' => [
                    'id' => Type::int(),
                    'url' => Type::string(),
                    'to_what' => Type::string(),
                ],
            ]);

            $itemType = new ObjectType([
                'name' => 'Items',
                'fields' => [
                    'id' => Type::int(),
                    'displayValue' => Type::string(),
                    'value' => Type::string(),
                    'item_id' => Type::string(),
                    'item_type' => Type::string(),
                ],
            ]);

            $currencyType = new ObjectType([
                'name' => 'Currency',
                'fields' => [
                    'id' => Type::int(),
                    'label' => Type::string(),
                    'symbol' => Type::string(),
                ]
            ]);

            $priceType = new ObjectType([
                'name' => 'Prices',
                'fields' => [
                    'id' => Type::int(),
                    'amount' => Type::float(),
                    'currency' => $currencyType,
                ]
            ]);

            $attributeType = new ObjectType([
                'name' => 'Attribute',
                'fields' => [
                    'id' => Type::int(),
                    'name' => Type::string(),
                    'type' => Type::string(),
                    'item_type' => Type::string(),
                    'items' => [
                        'type' => Type::listOf($itemType),
                        'resolve' => function ($root) use ($itemResolver, $conn) {
                            if (isset($root['item_type']) && !empty($root['item_type'])) {
                                return $itemResolver->getItemByType($conn, $root['item_type']);
                            }
                            return [];
                        }
                    ],
                ]
            ]);

            $productType = new ObjectType([
                'name' => 'Product',
                'fields' => [
                    'id' => Type::int(),
                    'product_id' => Type::string(),
                    'name' => Type::string(),
                    'inStock' => Type::boolean(),
                    'gallery' => Type::listOf($galleryType),
                    'description' => Type::string(),
                    'category' => Type::listOf($categoryType),
                    'attributes' => Type::listOf($attributeType),
                    'prices' => Type::listOf($priceType),
                    'brand' => Type::string(),
                ],
            ]);

            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'categories' => [
                        'type' => Type::listOf($categoryType),
                        'resolve' => function ($root, $args) use ($categoryResolver, $conn) {
                            return $categoryResolver->getCategories($conn);
                        },
                    ],
                    'categoryByID' => [
                        'type' => $categoryType,
                        'args' => [
                            'id' => ['type' => Type::int()],
                        ],
                        'resolve' => function ($root, $args) use ($categoryResolver, $conn) {
                            return $categoryResolver->getCategoryById($conn, $args['id']);
                        },
                    ],
                    'categoryByName' => [
                        'type' => $categoryType,
                        'args' => [
                            'name' => ['type' => Type::string()],
                        ],
                        'resolve' => function ($root, $args) use ($categoryResolver, $conn) {
                            return $categoryResolver->getCategoryByName($conn, $args['name']);
                        },
                    ],
                    'gallery' => [
                        'type' => Type::listOf($galleryType),
                        'resolve' => function () use ($galleryResolver, $conn) {
                            return $galleryResolver->getGallery($conn);
                        },
                    ],
                    'galleryByID' => [
                        'type' => $galleryType,
                        'args' => [
                            'id' => ['type' => Type::int()],
                        ],
                        'resolve' => function ($root, $args) use ($galleryResolver, $conn) {
                            return $galleryResolver->getGallleryByID($args['id'], $conn);
                        },
                    ],
                    'getGalleryByObject' => [
                        'type' => Type::listOf($galleryType),
                        'args' => [
                            'to_what' => ['type' => Type::string()],
                        ],
                        'resolve' => function ($root, $args) use ($galleryResolver, $conn) {
                            return $galleryResolver->getGalleryByObject($args['to_what'], $conn);
                        },
                    ],
                    'items' => [
                        'type' => Type::listOf($itemType),
                        'resolve' => function () use ($itemResolver, $conn) {
                            return $itemResolver->getItem($conn);
                        },
                    ],
                    'itemByID' => [
                        'type' => $itemType,
                        'args' => [
                            'id' => ['type' => Type::int()],
                        ],
                        'resolve' => function ($root, $args) use ($itemResolver, $conn) {
                            return $itemResolver->getItemByID($args['id'], $conn);
                        },
                    ],
                    'itemByValue' => [
                        'type' => Type::listOf($itemType),
                        'args' => [
                            'value' => ['type' => Type::string()],
                        ],
                        'resolve' => function ($root, $args) use ($itemResolver, $conn): mixed {
                            return $itemResolver->getItemByValue($args['value'], $conn);
                        },
                    ],
                    'prices' => [
                        'type' => Type::listOf($priceType),
                        'resolve' => function () use ($priceResolver, $conn) {
                            return $priceResolver->getPrice($conn);
                        },
                    ],
                    'pricesByID' => [
                        'type' => $priceType,
                        'args' => [
                            'id' => ['type' => Type::int()],
                        ],
                        'resolve' => function ($root, $args) use ($priceResolver, $conn) {
                            return $priceResolver->getPriceByID($args['id'], $conn);
                        },
                    ],
                    'pricesByAmount' => [
                        'type' => $priceType,
                        'args' => [
                            'amount' => ['type' => Type::float()],
                        ],
                        'resolve' => function ($root, $args) use ($priceResolver, $conn) {
                            return $priceResolver->getPriceByAmount($args['amount'], $conn);
                        },
                    ],
                    'products' => [
                        'type' => Type::listOf($productType),
                        'resolve' => function () use ($productResolver, $conn) {
                            return $productResolver->getProduct($conn);
                        },
                    ],
                    'productsByID' => [
                        'type' => $productType,
                        'args' => [
                            'id' => ['type' => Type::int()],
                        ],
                        'resolve' => function ($root, $args) use ($productResolver, $conn) {
                            return $productResolver->getProductById($conn, $args['id']);
                            ;
                        },
                    ],
                    'productsByProductID' => [
                        'type' => $productType,
                        'args' => [
                            'product_id' => ['type' => Type::string()],
                        ],
                        'resolve' => function ($root, $args) use ($productResolver, $conn) {
                            return $productResolver->getProductByProductID($conn, $args['product_id']);
                            ;
                        },
                    ],
                    'attributes' => [
                        'type' => Type::listOf($attributeType),
                        'resolve' => function () use ($attributeResolver, $conn) {
                            return $attributeResolver->getAttributes($conn);
                        },
                    ],
                    'attributesByID' => [
                        'type' => $attributeType,
                        'args' => [
                            'id' => ['type' => Type::int()],
                        ],
                        'resolve' => function ($root, $args) use ($attributeResolver, $conn) {
                            return $attributeResolver->getAttributesByID($args['id'], $conn);
                        },
                    ],
                    'attributesByType' => [
                        'type' => Type::listOf($attributeType),
                        'args' => [
                            'type' => ['type' => Type::string()],
                        ],
                        'resolve' => function ($root, $args) use ($attributeResolver, $conn) {
                            return $attributeResolver->getAttributesByType($args['type'], $conn);
                        },
                    ],
                ],
            ]);

            $orderType = new ObjectType([
                'name' => 'Order',
                'fields' => [
                    'id' => Type::nonNull(Type::int()),
                    'totalAmount' => Type::nonNull(Type::float()),
                    'status' => Type::nonNull(Type::string()),
                    'createdAt' => Type::nonNull(Type::string()),
                    'items' => Type::listOf(new ObjectType([
                        'name' => 'OrderItem',
                        'fields' => [
                            'productId' => Type::nonNull(Type::int()),
                            'quantity' => Type::nonNull(Type::int()),
                            'selectedAttributes' => Type::listOf(new ObjectType([
                                'name' => 'OrderItemAttribute',
                                'fields' => [
                                    'attributeId' => Type::nonNull(Type::int()),
                                    'value' => Type::nonNull(Type::string()),
                                ],
                            ])),
                        ],
                    ])),
                ],
            ]);


            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'placeOrder' => [
                        'type' => $orderType,
                        'args' => [
                            'items' => [
                                'type' => Type::listOf(new InputObjectType([
                                    'name' => 'OrderItemInput',
                                    'fields' => [
                                        'productId' => Type::nonNull(Type::int()),
                                        'quantity' => Type::nonNull(Type::int()),
                                        'selectedAttributes' => Type::listOf(new InputObjectType([
                                            'name' => 'OrderItemAttributeInput',
                                            'fields' => [
                                                'attributeId' => Type::nonNull(Type::int()),
                                                'value' => Type::nonNull(Type::string()),
                                            ],
                                        ])),
                                    ],
                                ])),
                            ],
                        ],
                        'resolve' => function ($root, $args) use ($orderResolver) {
                            return $orderResolver->newOrder($args['items']);
                        },
                    ],
                ],
            ]);

            $schema = new Schema([
                'query' => $queryType,
                'mutation' => $mutationType
            ]);

            $rawInput = file_get_contents('php://input');
            if (!$rawInput) {
                die(json_encode(["error" => "No input received"]));
            }

            $input = json_decode($rawInput, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                die(json_encode(["error" => "Invalid JSON", "details" => json_last_error_msg()]));
            }

            $query = $input['query'] ?? null;
            $variableValues = $input['variables'] ?? null;
            if (!$query) {
                die(json_encode(["error" => "Query is missing"]));
            }

            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            error_log("GraphQL Error: " . $e->getMessage());
            error_log("Trace: " . print_r($e->getTrace(), true));

            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($output);
    }
}

GraphQL::handle();