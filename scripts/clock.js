class Script {
  constructor() {
    // store list of order items
    this.orderItems = {};
    // store total order amount
    this.totalOrderAmount = 0.0;
    // store change
    this.change = -1;
    // tendered amount
    this.tenderedAmount = 0;

    this.products = products;
  }

  showClock() {
    const dateObj = new Date();
    const months = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];

    const year = dateObj.getFullYear();
    const monthNum = dateObj.getMonth();
    const dateCal = dateObj.getDate();
    const hour = dateObj.getHours();
    const min = dateObj.getMinutes();
    const sec = dateObj.getSeconds();

    const timeFormatted = this.toTwelveHourFormat(hour);
    document.querySelector(
      ".timeAndDate"
    ).innerHTML = `${months[monthNum]} ${dateCal}, ${year} ${timeFormatted.time}:${min}:${sec} ${timeFormatted.am_pm}`;
    setInterval(this.showClock.bind(this), 1000);
  }

  toTwelveHourFormat(time) {
    let am_pm = "AM";
    if (time > 12) {
      time -= 12;
      am_pm = "PM";
    }
    return { time, am_pm };
  }

  addOrder(pid, productInfo, orderQty) {
    if (
      pid === undefined ||
      productInfo === undefined ||
      orderQty === undefined
    ) {
      console.error("Error: pid, productInfo, or orderQty is undefined");
      return;
    }

    if (
      !productInfo.hasOwnProperty("price") ||
      !productInfo.hasOwnProperty("name")
    ) {
      console.error("Error: productInfo is missing price or name property");
      return;
    }

    const totalAmount = productInfo.price * orderQty;

    if (this.orderItems[pid]) {
      this.orderItems[pid].orderQty += orderQty;
      this.orderItems[pid].amount += totalAmount;
    } else {
      this.orderItems[pid] = {
        name: productInfo.name,
        price: productInfo.price,
        orderQty,
        amount: totalAmount,
      };
    }

    if (this.products[pid] === undefined) {
      console.error("Error: No product with the id " + pid);
    } else {
      this.products[pid].stock -= orderQty;
    }

    // Call updateOrderItemTable to refresh the table
    this.updateOrderItemTable();
  }

  updateOrderItemTable() {
    // reset to zero
    this.totalOrderAmount = 0.0;
    // refresh order list table
    const ordersContainer = document.querySelector(".screenItems");
    if (!ordersContainer) {
      console.error("ordersContainer element not found");
      return;
    }

    console.log("Updating order item table with orderItems:", this.orderItems);

    let html = '<p class="itemNoData">NO DATA</p>';

    if (Object.keys(this.orderItems).length > 0) {
      let tableHTML = `
            <table class="table" id="screenItems_tb1">
                <thead>
                    <tr>
                        <th>Row</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Order Quantity</th>
                        <th>Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    __ROWS__
                </tbody>
            </table>
        `;

      let rows = "";
      let rowNum = 1;

      for (const [pid, orderItem] of Object.entries(this.orderItems)) {
        rows += `
                <tr>
                    <td>${rowNum}</td>
                    <td>${orderItem.name}</td>
                    <td>${loadScript.addCommas(orderItem.price)}</td>
                    <td>${loadScript.addCommas(orderItem.orderQty)}
                        <a href="javascript:void(0); data-id="${pid}" class="quantityUpdateBtn quantityUpdateBtn_minus">
                        <i class="fa fa-minus quantityUpdateBtn quantityUpdateBtn_minus" data-id="${pid}"></i>
                        </a>
                        <a href="javascript:void(0); data-id="${pid}" class="quantityUpdateBtn quantityUpdateBtn_plus">
                        <i class="fa fa-plus quantityUpdateBtn quantityUpdateBtn_plus" data-id="${pid}"></i>
                        </a>
                    </td>
                    <td>${loadScript.addCommas(
                      Number(orderItem.amount).toFixed(2)
                    )}</td>
                    <td>
                        <a href="javascript:void(0);">
                        <i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0);" class="deleteOrderItem" data-id="${pid}">
                            <i class="fa fa-trash deleteOrderItem" data-id="${pid}"></i>
                        </a>
                    </td>
                </tr>
            `;
        rowNum++;

        loadScript.totalOrderAmount += orderItem.amount;
      }
      html = tableHTML.replace("__ROWS__", rows);
    }
    ordersContainer.innerHTML = html;

    loadScript.updateTotalOrderAmount();
  }
  updateTotalOrderAmount() {
    document.querySelector(".itemTotal-value").innerHTML = Number(
      loadScript.totalOrderAmount
    ).toFixed(2);
  }
  formatNum(num) {
    if (isNaN(num) || num === undefined) num = 0.0;
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
  addCommas(nStr) {
    nStr += "";
    var x = nStr.split(".");
    var x1 = x[0];
    var x2 = x.length > 1 ? "." + x[1] : "";
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, "$1" + "," + "$2");
    }
    return x1 + x2;
  }

  registerEvents() {
    const self = this;
    document.addEventListener("click", function (e) {
      const targetEl = e.target;
      const targetElClassList = targetEl.classList;
      const addToOrderClasses = ["productImage", "productName", "productPrice"];
      if (addToOrderClasses.some((cls) => targetElClassList.contains(cls))) {
        const productsContainer = targetEl.closest("div.productColContainer");
        const pid = productsContainer.dataset.pid;
        const productInfo = self.products[pid];
        if (!productInfo) {
          console.error("Product information not found for pid:", pid);
          return;
        }
        self.createModal(pid, productInfo);
        if (productInfo.stock <= 0) {
          alert("Out of stock");
          return;
        }
      }

      // Delete order item
      if (targetElClassList.contains("deleteOrderItem")) {
        const pid = targetEl.dataset.id;
        const productInfo = loadScript.orderItems[pid];

        let toDelete = window.confirm(
          "Delete Order\nAre you sure to delete " + productInfo["name"] + "?"
        );
        if (toDelete) {
          // get the quantity ordered and move it back to the main product info - orderItems
          let orderedQuantity = productInfo["orderQty"];
          loadScript.products[pid]["stock"] += orderedQuantity;

          // delete items from order item
          delete loadScript.orderItems[pid];

          // refresh table or delete row
          loadScript.updateOrderItemTable();

          console.log(productInfo);
        }
      }
      // update qty
      // decrease qty
      if (targetElClassList.contains("quantityUpdateBtn_minus")) {
        const pid = targetEl.dataset.id;

        // update products list qty - add 1,
        loadScript.products[pid]["stock"]++;
        // update orderItem - orderQty - minus 1
        loadScript.orderItems[pid]["orderQty"]--;

        // update new amount
        loadScript.orderItems[pid]["amount"] =
          loadScript.orderItems[pid]["orderQty"] *
          loadScript.orderItems[pid]["price"];

        // if orderQty becomes zero, let's delete it from list
        if (loadScript.orderItems[pid]["orderQty"] === 0) {
          delete loadScript.orderItems[pid];
        }

        console.log(loadScript.orderItems[pid]);

        // refresh table or delete row
        loadScript.updateOrderItemTable();
      }
      // decrease qty
      if (targetElClassList.contains("quantityUpdateBtn_plus")) {
        const pid = targetEl.dataset.id;

        // check if stock is empty
        // show alert
        if (loadScript.products[pid]["stock"] === 0) {
          alert("Out of stock");
          return;
        } else {
          // update products list qty - minus 1,
          loadScript.products[pid]["stock"]--;
          // update orderItem - orderQty - plus 1
          loadScript.orderItems[pid]["orderQty"]++;

          // update new amount
          loadScript.orderItems[pid]["amount"] =
            loadScript.orderItems[pid]["orderQty"] *
            loadScript.orderItems[pid]["price"];

          // if orderQty becomes zero, let's delete it from list
          if (loadScript.orderItems[pid]["orderQty"] === 0) {
            delete loadScript.orderItems[pid];
          }

          console.log(loadScript.orderItems[pid]);

          // refresh table or delete row
          loadScript.updateOrderItemTable();
        }
      }

      if (targetElClassList.contains("proceedBtn")) {
        // Check if order items are empty
        if (Object.keys(self.orderItems).length > 0) {
          // Display items, total amount, and input field to enter amount
          let orderItemsHTML = "";
          let counter = 1;
          let totalAmount = 0.0;

          for (const [pid, orderItem] of Object.entries(self.orderItems)) {
            orderItemsHTML += `
            <div class="row proceedTblContentContainter">
                <div class="col-md-2 proceedTblContent">${counter}</div>
                <div class="col-md-4 proceedTblContent">${orderItem.name}</div>
                <div class="col-md-3 proceedTblContent">${self.addCommas(
                  orderItem.orderQty
                )}</div>
                <div class="col-md-3 proceedTblContent">${self.addCommas(
                  orderItem.amount.toFixed(2)
                )}</div>
            </div>
            `;
            totalAmount += orderItem.amount;
            counter++;
          }

          let content =
            `
          <div class="row">
              <div class="col-md-7">
              <p class="proceedTblHeaderContainer_title">Items</p>
              <div class="row proceedTblHeaderContainer">
                  <div class="col-md-2 proceedTblHeader">#</div>
                  <div class="col-md-4 proceedTblHeader">Product Name</div>
                  <div class="col-md-3 proceedTblHeader">Ordered Qty</div>
                  <div class="col-md-3 proceedTblHeader">Amount</div>
              </div>` +
            orderItemsHTML +
            `
              <div class="col-md-5">
              <div class="proceedTotalAmountContainer">
                  <span class="proceed_amt">` +
            self.addCommas(totalAmount.toFixed(2)) +
            `</span> <br/>
                  <span class="proceed_amt_title">TOTAL AMOUNT</span>
              </div>
              <div class="proceedUserAmt">
                  <input type="text" class="form-control" id="userAmt" placeholder="Enter Amount"/>
              </div>
              <div class="proceedUserChangeContainer">
                  <p class="proceedUserChange"><small>CHANGE: </small><span class="changeAmt"> 0.00</span></p>
              </div>
          </div>
          </hr>
          <div class="proceedCustomer">
              <p class="proceedCustomerTitle">Customer</p>
                <form class="form-horizontal" action="insertDB.php" method="post" enctype="multipart/form-data">
					<div class="control-group">
						<label class="control-label"><font color="aqua">ID</label>
						<div class="controls">
							<textarea name="id" id="getUID" placeholder="Please Scan your Card / Key Chain to display ID" rows="1" cols="1" required></textarea>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label fName">Family Name</label>
						<div class="controls">
							<input id="fName" name="fname" type="text"  placeholder="" required>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label bName">Main Beneficiary Name</label>
						<div class="controls">
							<input id="bName" name="bname" type="text"  placeholder="" required>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Gender</label>
						<div class="controls">
							<select name="gender" id="gender">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Email Address</label>
						<div class="controls">
							<input id="email" name="email" type="text" placeholder="" required>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Mobile Number</label>
						<div class="controls">
							<input id="mobile" name="mobile" type="text"  placeholder="" required>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Proof of Residence</label>
						<div class="controls">
							<input id="proof" name="proof" type="file"  placeholder="" required>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Complete Address</label>
						<div class="controls">
							<input id="address" name="address" type="text" placeholder="" required>
						</div>
					</div>

				</form>
		</div>
          `;

          // Create modal element
          let modal = document.createElement("div");
          modal.style.display = "block";
          modal.style.position = "fixed";
          modal.style.zIndex = "1";
          modal.style.left = "0";
          modal.style.top = "0";
          modal.style.width = "100%";
          modal.style.height = "100%";
          modal.style.overflow = "auto";
          modal.style.backgroundColor = "rgba(0,0,0,0.4)";

          let dialog = document.createElement("div");
          dialog.style.backgroundColor = "#fefefe";
          dialog.style.margin = "15% auto";
          dialog.style.padding = "20px";
          dialog.style.border = "1px solid #888";
          dialog.style.width = "80%";

          let dialogTitle = document.createElement("h2");
          dialogTitle.innerText = "PROCEED";
          dialogTitle.style.textAlign = "center";
          dialogTitle.style.fontSize = "30px";

          let dialogMessage = document.createElement("div");
          dialogMessage.innerHTML = content; // assuming 'content' is a variable holding your message

          let cancelButton = document.createElement("button");
          cancelButton.innerText = "CANCEL";
          cancelButton.style.margin = "10px";
          cancelButton.style.padding = "10px";
          cancelButton.onclick = function () {
            document.body.removeChild(modal);
          };

          let confirmButton = document.createElement("button");
          confirmButton.innerText = "PROCEED";
          confirmButton.style.margin = "10px";
          confirmButton.style.padding = "10px";
          confirmButton.onclick = function (proceed) {
            if (proceed) {
              if (loadScript.userChange < 0) {
                loadScript.dialogError("Insufficient amount.");
                return;
              } else {
                // save to database
                $.post(
                  "product.php?action=add_product",
                  {
                    data: loadScript.orderItems,
                    totalAmt: loadScript.totalOrderAmount,
                    change: loadScript.userChange,
                    tenderedAmount: loadScript.tenderedAmount,
                    customer: {
                      UID: document.getElementById("getUID").value,
                      familyName: document.getElementById("fName").value,
                      beneficiaryName: document.getElementById("bName").value,
                      gender: document.getElementById("gender").value,
                      email: document.getElementById("email").value,
                      mobile: document.getElementById("mobile").value,
                      proof: document.getElementById("proof").value,
                    },
                  },
                  function (response) {
                    let message = response.success
                      ? "You may tap in your RFID Card"
                      : "Error: Try again";
                    alert(message); // Display the alert dialog with the message

                    let alertType = response.success
                      ? "alert-success"
                      : "alert-danger";

                    // Create a new div element for the alert
                    let alertDiv = document.createElement("div");
                    alertDiv.className = `alert ${alertType} alert-dismissible fade show`;
                    alertDiv.setAttribute("role", "alert");

                    // Create a new paragraph element for the alert message
                    let alertMessage = document.createElement("p");
                    alertMessage.textContent = response.message;

                    // Create a new button element for the close button
                    let closeButton = document.createElement("button");
                    closeButton.className = "close";
                    closeButton.setAttribute("type", "button");
                    closeButton.setAttribute("data-dismiss", "alert");
                    closeButton.setAttribute("aria-label", "Close");

                    // Create a new span element for the close button icon
                    let closeIcon = document.createElement("span");
                    closeIcon.setAttribute("aria-hidden", "true");
                    closeIcon.textContent = "&times;";

                    // Append the close icon to the close button
                    closeButton.appendChild(closeIcon);

                    // Append the close button to the alert div
                    alertDiv.appendChild(closeButton);

                    // Append the alert message to the alert div
                    alertDiv.appendChild(alertMessage);

                    // Append the alert div to the body
                    document.body.appendChild(alertDiv);

                    // Remove the alert after 5 seconds
                    setTimeout(function () {
                      alertDiv.remove();
                    }, 1000);

                    setInterval(function () {
                      // Call your refresh function here
                      reloadCard();
                    }, 1000);

                    // Reset the data if the operation was successful
                    if (response.success) {
                      loadScript.resetData(response);
                      window.open("/medicard/read tag copy.php", "_self");
                    }
                  },
                  "json"
                );
              }
            }
          };

          dialog.appendChild(dialogTitle);
          dialog.appendChild(dialogMessage);
          dialog.appendChild(cancelButton);
          dialog.appendChild(confirmButton);
          modal.appendChild(dialog);
          document.body.appendChild(modal);
        } else {
          console.error("Error: No order items to proceed with");
        }
      }
    });
    document.addEventListener("change", function (e) {
      const targetEl = e.target;
      let targetElClassList = targetEl.classList;

      if (targetEl.id === "userAmt") {
        let userAmt = targetEl.value == "" ? 0 : parseFloat(targetEl.value);
        loadScript.tenderedAmount = userAmt;
        let change = userAmt - loadScript.totalOrderAmount;
        loadScript.userChange = change;

        document.querySelector(".proceedUserChange .changeAmt").innerHTML =
          loadScript.addCommas(change.toFixed(2));

        let el = document.querySelector(".proceedUserChange");

        if (change < 0) {
          loadScript.dialogError("Insufficient amount.");
        } else {
          loadScript.userChange = change;
        }
      }
    });
  }

  resetData(response) {
    // Check if response and response.products exist
    if (response && response.products) {
      // update products variable
      let productsJson = response.products;
      loadScript.products = {};

      // loop through productsJson and push to products array
      productsJson.forEach((product) => {
        loadScript.products[product.id] = {
          name: product.product_name,
          stock: product.stock,
          price: product.price,
        };
      });
    }

    // store list of order items
    loadScript.orderItems = {};
    // store total order amount
    loadScript.totalOrderAmount = 0.0;
    // store change
    loadScript.change = -1;
    // tendered amount
    loadScript.tenderedAmount = 0;
    // reset table
    loadScript.updateOrderItemTable();
  }

  createModal(pid, productInfo) {
    const dialogForm = `
            <h6 class="dialogProductName">${productInfo.name} <span>$ ${productInfo.price}</span></h6>
            <input id="orderQty" type="number" class="form-control" placeholder="Enter quantity..." />
        `;

    const modal = document.createElement("div");
    modal.style.display = "block";
    modal.style.position = "fixed";
    modal.style.zIndex = "1";
    modal.style.left = "0";
    modal.style.top = "0";
    modal.style.width = "100%";
    modal.style.height = "100%";
    modal.style.overflow = "auto";
    modal.style.backgroundColor = "rgba(0,0,0,0.4)";

    const modalContent = document.createElement("div");
    modalContent.style.backgroundColor = "#fefefe";
    modalContent.style.margin = "15% auto";
    modalContent.style.padding = "20px";
    modalContent.style.border = "1px solid #888";
    modalContent.style.width = "80%";

    const modalHeader = document.createElement("h2");
    modalHeader.innerText = "Add to Cart";
    modalHeader.style.textAlign = "center";
    modalHeader.style.fontSize = "30px";

    const formContainer = document.createElement("div");
    formContainer.innerHTML = dialogForm;

    const buttonContainer = document.createElement("div");
    buttonContainer.style.textAlign = "right";

    const backButton = document.createElement("button");
    backButton.innerText = "Back";
    backButton.style.margin = "10px";
    backButton.style.padding = "10px";
    backButton.onclick = function () {
      document.body.removeChild(modal);
    };

    const okButton = document.createElement("button");
    okButton.innerText = "OK";
    okButton.style.margin = "10px";
    okButton.style.padding = "10px";
    okButton.onclick = function () {
      const orderQty = parseInt(document.getElementById("orderQty").value);

      if (isNaN(orderQty)) {
        alert("Please enter a valid quantity.");
        return;
      }
      if (orderQty > productInfo.stock) {
        alert(
          "Quantity is higher than the current stock. (" +
            productInfo.stock +
            ")"
        );
        return;
      }
      loadScript.addOrder(pid, productInfo, orderQty);
      document.body.removeChild(modal);
    };

    buttonContainer.appendChild(backButton);
    buttonContainer.appendChild(okButton);

    modalContent.appendChild(modalHeader);
    modalContent.appendChild(formContainer);
    modalContent.appendChild(buttonContainer);
    modal.appendChild(modalContent);
    document.body.appendChild(modal);
  }

  initialize() {
    this.showClock();
    this.registerEvents();

    $(document).ready(function () {
      $("#getUID").load("rfid/UIDContainer.php");
      setInterval(function () {
        $("#getUID").load("rfid/UIDContainer.php");
      }, 500);
    });
  }
}

const loadScript = new Script();
loadScript.initialize();
