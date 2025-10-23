# 🧪 QUICK TESTING GUIDE - Admin Order Management

## ✅ Pre-Testing Checklist

Before testing, make sure:
- [ ] Database migrations are run (`php artisan migrate`)
- [ ] You're logged in as an admin user
- [ ] There are some orders in the database (or create test orders)
- [ ] Stock is available for products

---

## 🔗 URLs to Test

### **Admin Order Management**:
```
http://localhost:8000/admin/orders              - Orders List
http://localhost:8000/admin/orders/1            - Order Details (replace 1 with actual order ID)
http://localhost:8000/admin/orders/1/invoice    - Invoice (replace 1 with actual order ID)
```

### **Customer Order Flow**:
```
http://localhost:8000/cart                      - View Cart
http://localhost:8000/checkout                  - Checkout
http://localhost:8000/orders                    - My Orders
http://localhost:8000/orders/1                  - Order Details
http://localhost:8000/track-order               - Track Order
```

---

## 📱 Mobile Testing

### **Quick Mobile Test** (Chrome DevTools):
1. Open Chrome DevTools (F12)
2. Click Toggle Device Toolbar (Ctrl+Shift+M)
3. Test these widths:
   - 320px (iPhone SE)
   - 375px (iPhone X)
   - 768px (iPad)
   - 1024px (Desktop)

### **Check These Elements**:
- [ ] Sidebar hidden on mobile (<768px)
- [ ] Statistics cards show 2 per row (col-6)
- [ ] Filters stack vertically
- [ ] Desktop table hidden on mobile
- [ ] Mobile cards show on small screens
- [ ] Buttons are touch-friendly (44px+)
- [ ] Text is readable (no tiny fonts)
- [ ] Forms are usable
- [ ] Action buttons work

---

## 🎯 Test Scenarios

### **Test 1: View Orders List**
1. Navigate to `/admin/orders`
2. ✅ Page loads without errors
3. ✅ Statistics cards show correct numbers
4. ✅ Orders list displays (table on desktop, cards on mobile)
5. ✅ Status badges show correct colors
6. ✅ Pagination works (if more than 20 orders)

### **Test 2: Filter Orders**
1. On orders list page
2. Select status filter → Click "Filtrer"
3. ✅ Orders filtered correctly
4. ✅ URL updates with parameter
5. Try payment status filter
6. ✅ Multiple filters work together
7. Click "Réinitialiser"
8. ✅ Filters reset, all orders show

### **Test 3: Search Orders**
1. On orders list page
2. Enter order number in search
3. Click search button
4. ✅ Correct order shows
5. Try partial order number
6. ✅ Search works

### **Test 4: View Order Details**
1. Click "Voir" on any order
2. ✅ Details page loads
3. ✅ All order info displays
4. ✅ Customer info shows
5. ✅ Items table/cards show correctly
6. ✅ Order summary calculates correctly
7. ✅ Payment info displays
8. ✅ Forms are present
9. ✅ Action buttons work

### **Test 5: Update Order Status**
1. On order details page
2. Change status in dropdown
3. Click save button
4. ✅ Success message shows
5. ✅ Status badge updates
6. ✅ Timestamp updated (check history card)
7. Try changing to "cancelled"
8. ✅ Stock restored (check product)

### **Test 6: Update Payment Status**
1. On order details page
2. Change payment status
3. Add payment reference (optional)
4. Click save button
5. ✅ Success message shows
6. ✅ Payment badge updates
7. ✅ Reference displays in payment info
8. Try marking as "paid"
9. ✅ Paid timestamp shows in history

### **Test 7: Add Tracking Number**
1. On order details page
2. Enter tracking number (e.g., DHL123456)
3. Click save button
4. ✅ Success message shows
5. ✅ Status auto-changed to "shipped"
6. ✅ Tracking number displays
7. ✅ Shipped timestamp in history

### **Test 8: Generate Invoice**
1. On order details page
2. Click "Voir la Facture"
3. ✅ Invoice opens in new tab
4. ✅ All order details correct
5. ✅ Company info displays
6. ✅ Customer info correct
7. ✅ Items list complete
8. ✅ Totals calculate correctly
9. Click "Imprimer la Facture"
10. ✅ Print dialog opens
11. ✅ Print preview looks good

### **Test 9: Delete Order**
1. Find a cancelled or delivered order
2. On order details page
3. Click "Supprimer la Commande"
4. ✅ Confirmation dialog shows
5. Confirm deletion
6. ✅ Redirects to orders list
7. ✅ Order no longer appears
8. Try deleting pending order
9. ✅ Delete button not shown (correct)

### **Test 10: Mobile Responsiveness**
1. Resize browser to 320px
2. Go to orders list
3. ✅ Statistics cards readable
4. ✅ Filters usable
5. ✅ Cards display properly
6. ✅ Buttons tappable
7. Go to order details
8. ✅ Layout stacks correctly
9. ✅ Forms work
10. ✅ No horizontal scroll

---

## 🐛 Common Issues & Fixes

### **Issue 1: Routes not found (404)**
**Cause**: Routes not registered  
**Fix**: Check `routes/web.php`, clear route cache:
```bash
php artisan route:clear
php artisan route:cache
```

### **Issue 2: Orders not displaying**
**Cause**: No orders in database  
**Fix**: Create test orders through checkout or seed database

### **Issue 3: Statistics showing 0**
**Cause**: No orders or incorrect status  
**Fix**: Check order statuses in database

### **Issue 4: Images not showing**
**Cause**: Storage link not created  
**Fix**: Run `php artisan storage:link`

### **Issue 5: Forms not submitting**
**Cause**: CSRF token issue  
**Fix**: Clear cache: `php artisan cache:clear`

### **Issue 6: Mobile layout broken**
**Cause**: CSS not loaded  
**Fix**: Run `npm run build` or `npm run dev`

### **Issue 7: Delete not working**
**Cause**: Order status not cancelled/delivered  
**Fix**: Only cancelled or delivered orders can be deleted

### **Issue 8: Stock not updating**
**Cause**: Transaction rollback or error  
**Fix**: Check laravel.log for errors

---

## ✅ Expected Results

### **Admin Orders List Page**:
- Statistics cards: 4 cards showing counts and revenue
- Filters: 4 filter fields working
- Table view: 8 columns on desktop
- Card view: Compact cards on mobile
- Actions: 3 buttons per order (View, Invoice, Delete)
- Pagination: Shows if more than 20 orders

### **Admin Order Details Page**:
- Order info: 4 fields displayed
- Customer info: Complete shipping details
- Items: All items with images, prices
- Order summary: Correct calculations
- Payment info: All payment details
- 3 Forms: Status, Payment, Tracking
- Actions: 3-4 action buttons
- History: All timestamps

### **Invoice Page**:
- Professional design with gradient header
- Complete order and customer info
- All items listed with totals
- Payment details
- Order history
- Print button works
- Back button works

---

## 📊 Performance Checklist

- [ ] Page loads in < 2 seconds
- [ ] No console errors (F12)
- [ ] No PHP errors in log
- [ ] Images load properly
- [ ] Forms submit without delay
- [ ] Filters respond quickly
- [ ] Search is fast
- [ ] Mobile performance good

---

## 🎯 Final Checklist

### **Functionality** ✅
- [ ] All routes accessible
- [ ] All forms submit correctly
- [ ] All filters work
- [ ] Search works
- [ ] Status updates work
- [ ] Payment updates work
- [ ] Tracking updates work
- [ ] Invoice generates correctly
- [ ] Delete works (when allowed)
- [ ] Pagination works

### **Design** ✅
- [ ] Matches admin theme
- [ ] Status badges colored correctly
- [ ] Icons display
- [ ] Layout clean
- [ ] No overlapping elements
- [ ] Spacing consistent
- [ ] Cards shadow correctly
- [ ] Buttons styled properly

### **Mobile** ✅
- [ ] Responsive on all sizes
- [ ] Sidebar hidden on mobile
- [ ] Cards show on small screens
- [ ] Buttons touch-friendly
- [ ] Text readable
- [ ] No horizontal scroll
- [ ] Forms usable
- [ ] Navigation works

### **Security** ✅
- [ ] Auth required
- [ ] Admin middleware working
- [ ] CSRF tokens present
- [ ] Users can't access other orders (customer side)
- [ ] Validation prevents bad data

---

## 🚀 Quick Test Commands

```bash
# Check routes
php artisan route:list --path=admin/orders

# Check for errors
tail -f storage/logs/laravel.log

# Clear all caches
php artisan optimize:clear

# Run database migrations
php artisan migrate

# Create storage link
php artisan storage:link
```

---

## 📝 Test Report Template

```
Date: __________
Tester: __________

✅ Admin Orders List: PASS / FAIL
✅ Order Details: PASS / FAIL
✅ Invoice: PASS / FAIL
✅ Filters: PASS / FAIL
✅ Search: PASS / FAIL
✅ Update Status: PASS / FAIL
✅ Update Payment: PASS / FAIL
✅ Add Tracking: PASS / FAIL
✅ Delete Order: PASS / FAIL
✅ Mobile 320px: PASS / FAIL
✅ Mobile 768px: PASS / FAIL

Issues Found:
1. ___________________________
2. ___________________________
3. ___________________________

Overall Status: READY / NEEDS FIXES
```

---

## 🎉 Success Criteria

**Admin order management is considered working if**:
1. ✅ All 7 routes are accessible
2. ✅ Orders list displays with filters
3. ✅ Order details show complete info
4. ✅ All 3 update forms work
5. ✅ Invoice generates and prints
6. ✅ Mobile responsive on all screen sizes
7. ✅ No console or server errors
8. ✅ Statistics calculate correctly

**If all criteria met**: 🎉 **SYSTEM READY FOR PRODUCTION!**

---

*Happy Testing! 🧪*
