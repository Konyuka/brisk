import { createStore } from "vuex";

export default createStore({
    state: {
        products: [
            {
                id: 1,
                name: "Chelsea Shoes",
                price: 200,
                shortdesc: "Best Drip in the Market",
                url: "images/chelsea-shoes.png"
            }
        ],
        cart: []
    },
    mutations: {
        addCartItem(state, item)
        {
            item.quantity = 1;
            state.cart.push(item);
        },
        updateCartItem(state, updatedItem)
        {
            state.cart = state.cart.map((cartItem) =>
            {
                if (cartItem.id == updatedItem.id) {
                    return updatedItem;
                }

                return cartItem;
            });
        },
        removeCartItem(state, item)
        {
            state.cart = state.cart.filter((cartItem) =>
            {
                return cartItem.id != item.id;
            });
        }
    }
});