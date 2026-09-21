export default function ticketSelector(ticketTypes, currency) {
    return {
        types: ticketTypes,
        currency: currency,
        quantities: Object.fromEntries(ticketTypes.map((t) => [t.id, 0])),

        increment(id) {
            const type = this.types.find((t) => t.id === id);
            const max = type?.max;

            if (max === null || max === undefined || this.quantities[id] < max) {
                this.quantities[id]++;
            }
        },

        decrement(id) {
            if (this.quantities[id] > 0) {
                this.quantities[id]--;
            }
        },

        totalQuantity() {
            return Object.values(this.quantities).reduce((sum, qty) => sum + qty, 0);
        },

        totalPrice() {
            return this.types.reduce((sum, type) => sum + type.price * (this.quantities[type.id] || 0), 0);
        },

        formattedTotal() {
            return this.currency + ' ' + this.totalPrice().toLocaleString();
        },
    };
}
