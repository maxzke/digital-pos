    /**
     * 
     * Obtiene Carrito de la BD
     *  
     */

    const mesa = document.getElementById('mesa');
    let idmesa = mesa.dataset.idmesa;
    let baseurl = mesa.dataset.baseurl;
    const urlrequest = baseurl+'//carrito/getCarrito';
    getCarrito(idmesa,urlrequest);
    
    async function getCarrito(id,url){
        const respuesta = await ajaxCarrito(id,url);
        if(respuesta.success){
            let str = respuesta.carrito.carrito;            
            localStorage.setItem("shoppingCart",str );
            mostrarCarrito(JSON.parse(localStorage.getItem("shoppingCart")));
        }else{
            localStorage.setItem("shoppingCart","[]" );
        }
    }
    async function ajaxCarrito(id_mesa,url){
        const response = $.ajax({
            type: 'ajax',
            method: 'post',
            url: url,
            data:{id:id_mesa},
            async: true,
            dataType: 'json'
        });
        const data = await response;
        return data;
    }
    function mostrarCarrito(cartArray){
        $("#show-cart").html(" ");
        let output ="";
        let ttalCar = 0;
        if (cartArray.length <= 0) {
                output = "<div class='mt-4 display-4 text-primary'><i class='fas fa-lg fa-shopping-cart'></i> </div><p class='text-muted h5'>Mesa vacía </p >";
        }else{
                for (var i in cartArray) {
                    precioUnitario = (cartArray[i].price) / cartArray[i].count;
                    subtotaltem = cartArray[i].price;
                    let newFormato = new Intl.NumberFormat("en-IN").format(subtotaltem);
                    ttalCar = ttalCar + subtotaltem;
                    output += `<div class='row cart-each-product'>
                            <div class='col-12 col-md-5 texto_articulo'>`+ cartArray[i].name +`</div>
                            <div class='col-3 offset-4 col-md-3 offset-md-0 align-self-center'>
                            ` + cartArray[i].count + `
                            </div>
                            <div class='col-3 col-md-2 align-self-center texto_articulo'>
                                ` + precioUnitario + `</i>
                            </div>
                            <div class='col-3 col-md-2 align-self-center texto_articulo'>
                                ` + newFormato + `</i>
                            </div>                                                        
                        </div>
                        </div>
                            <hr>`;
                }
            
            }
        $("#show-cart").html(output);
        let ct = new Intl.NumberFormat("en-IN").format(ttalCar);
        let str = "Total $ "+ct;
        $("#totalCarrito").html(str);
    }
    /**
     * 
     * Guarda Carrito en la BD
     *  
     */
    async function insertCarrito(id,url,carrito){      
        const respuesta = await ajaxInsertCarrito(id,url,carrito);
        console.log(respuesta);
        if(respuesta.success){
            let str = respuesta;         
            console.log(str);
            localStorage.setItem("shoppingCart",str );   
        }else{
            this.cart = [];
        }        
    }
    async function ajaxInsertCarrito(id_mesa,url,carrito){
        const response = $.ajax({
            type: 'ajax',
            method: 'post',
            url: url,
            data:{id:id_mesa,carrito:carrito.toString()},
            async: true,
            dataType: 'json'
        });
        const data = await response;
        return data;
    }



/*
 * jQuery Simple Shopping Cart v0.1
 * Basis shopping cart using javascript/Jquery.
 *
 * Authour : Sirisha
 */
/* '(function(){})();' this function is used, to make all variables of the plugin Private */
(function ($, window, document, undefined) {

    /* Default Options */
    var defaults = {
        cart: [],
        addtoCartClass: '.sc-add-to-cart',
        cartProductListClass: '.cart-products-list',
        totalCartCountClass: '.total-cart-count',
        totalCartCostClass: '.total-cart-cost',
        showcartID : '#show-cart',
        itemCountClass : '.item-count',
        vaciarCarrito : '.limpiar_carrito'
    };

    function Item(name, price, count) {
        this.name = name;
        this.price = price;
        this.count = count;
    }
    /*Constructor function*/
    function simpleCart(domEle, options) {

        /* Merge user settings with default, recursively */
        this.options = $.extend(true, {}, defaults, options);
        //Cart array
        this.cart = [];
        //Dom Element
        this.cart_ele = $(domEle);
        //Initial init function
        this.init();
    }


    /*plugin functions */
    $.extend(simpleCart.prototype, {
        init: function empieza() {
            this._setupCart();
            this._setEvents();
            this._loadCart();
            this._updateCartDetails();
        },
        _setupCart: function () {
            this.cart_ele.addClass("cart-grid panel panel-defaults");
            this.cart_ele.append(`<div class='panel-body cart-body'>
                                    <div class='cart-products-list' id='show-cart'>
                                        <!-- Dynamic Code from Script comes here-->
                                    </div>
                                </div>`);
        },
        _addProductstoCart: function () {
        },
        _updateCartDetails: function () {
            var mi = this;
            $(this.options.cartProductListClass).html(mi._displayCart());
            $(this.options.totalCartCountClass).html(" " + mi._totalCartCount() + " Artículos");
            let str = "Total $ "+mi._totalCartCost();
            $(this.options.totalCartCostClass).html(str);
            
        },
        _setCartbuttons: function () {

        },
        _setEvents: function () {
            var mi = this;
            $(document).on("click",this.options.addtoCartClass, function (e) {
                e.preventDefault();
                var name = $(this).attr("data-name");
                var cost = Number($(this).attr("data-price"));
                var amount = Number($(this).attr("data-quantity"));                
                var nombreMesa = $(this).attr("data-mesa");
                var categoria = $(this).attr("data-categoria");
                mi._addItemToCart(name, cost, amount);
                mi._sendToCocina(name,nombreMesa,categoria);
                mi._updateCartDetails();
            });

            $(this.options.showcartID).on("change", this.options.itemCountClass, function (e) {
                var ci = this;
                e.preventDefault();
                var count = $(this).val();
                var name = $(this).attr("data-name");
                var cost = Number($(this).attr("data-price"));
                mi._removeItemfromCart(name, cost, count);
                mi._updateCartDetails();
            });

            $(document).on("click",this.options.vaciarCarrito, function (e) {
                mi._clearCart();
                mi._updateCartDetails();
            });

        },
        _sendToCocina: function(name,mesa,categoria){
            console.log('nombre: '+name+'mesa: '+mesa+'categoria: '+categoria);
            $.ajax({
                type: 'ajax',
                method: 'post',
                url: baseurl+'/cocina/addorden',
                data:{producto:name,mesa:mesa,categoria:categoria},
                async: true,
                dataType: 'json'
            });
        },
        /* Helper Functions */
        _addItemToCart: function (name, price, count) {
            for (var i in this.cart) {
                if (this.cart[i].name === name) {
                    this.cart[i].count++;
                    this.cart[i].price = price * this.cart[i].count;
                    this._saveCart();
                    return;
                }
            }
            var item = new Item(name, price, count);
            this.cart.push(item);
            this._saveCart();
        },
        _removeItemfromCart: function (name, price, count) {
            for (var i in this.cart) {
                if (this.cart[i].name === name) {
                    var singleItemCost = Number(price / this.cart[i].count);
                    this.cart[i].count = count;
                    this.cart[i].price = singleItemCost * count;
                    if (count == 0) {
                        this.cart.splice(i, 1);
                    }
                    break;
                }
            }
            this._saveCart();
        },
        _clearCart: function () {
            this.cart = [];
            this._saveCart();
        },
        _totalCartCount: function () {
            return this.cart.length;
        },
        _displayCart: function () {
            var cartArray = this._listCart();
            //console.log(cartArray);
            var output = "";
            let precioUnitario = 0;
            let subtotaltem = 0;
            if (cartArray.length <= 0) {
                output = "<div class='mt-4 display-4 text-primary'><i class='fas fa-lg fa-shopping-cart'></i> </div><p class='text-muted h5'>Su carro de compra esta vacío </p >";
            }else{
                for (var i in cartArray) {
                    precioUnitario = (cartArray[i].price) / cartArray[i].count;
                    subtotaltem = cartArray[i].price;
                    let newFormato = new Intl.NumberFormat("en-IN").format(subtotaltem);
                    output += `<div class='row cart-each-product'>
                            <div class='col-12 col-md-5 texto_articulo'>`+ cartArray[i].name +`</div>
                            <div class='col-3 offset-4 col-md-3 offset-md-0 align-self-center'>
                            ` + cartArray[i].count + `
                            </div>
                            <div class='col-3 col-md-2 align-self-center texto_articulo'>
                                ` + precioUnitario + `</i>
                            </div>
                            <div class='col-3 col-md-2 align-self-center texto_articulo'>
                                ` + newFormato + `</i>
                            </div>                                                        
                        </div>
                        </div>
                            <hr>`;
                }
            }
            
            return output;
        },
        _totalCartCost: function () {
            var totalCost = 0;
            for (var i in this.cart) {
                totalCost += this.cart[i].price;
            }
            let unDecimal = totalCost.toFixed(1);
            let formato = new Intl.NumberFormat("en-IN").format(unDecimal);
            return formato;
        },
        _listCart: function () {
            var cartCopy = [];
            for (var i in this.cart) {
                var item = this.cart[i];
                var itemCopy = {};
                for (var p in item) {
                    itemCopy[p] = item[p];
                }
                cartCopy.push(itemCopy);
            }
            return cartCopy;
        },
        _calGST: function () {
            var GSTPercent = 18;
            var totalcost = this.totalCartCost();
            var calGST = Number((totalcost * GSTPercent) / 100);
            return calGST;
        },
        _saveCart: function () {
            localStorage.setItem("shoppingCart", JSON.stringify(this.cart));
            let urlrequest = baseurl+'/carrito/insertUpdate';
            let cart = JSON.stringify(this.cart);
            insertCarrito(idmesa,urlrequest,cart);
        },
        _loadCart: function () {
            this.cart = [];
            this.cart = JSON.parse(localStorage.getItem("shoppingCart"));
            if (this.cart === null) {
                this.cart = [];
            } 
        }
    });
    /* Defining the Structure of the plugin 'simpleCart'*/
    $.fn.simpleCart = function (options) {
        return this.each(function () {
            $.data(this, "simpleCart", new simpleCart(this));
            //console.log($(this, "simpleCart"));
        });
    };
})(jQuery, window, document)



