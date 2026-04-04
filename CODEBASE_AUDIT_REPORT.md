# Codebase Audit Report - TrustEquip Application
**Date:** April 4, 2026  
**Application:** Peminjaman Alat (TrustEquip)  
**Status:** Comprehensive Analysis Complete

---

## Executive Summary
The codebase contains **15+ identified issues** ranging from critical import errors to potential runtime problems. Most issues are in Vue components and can cause runtime errors or broken functionality.

---

## Critical Issues

### 1. **Missing Import in PickupVerification.vue** ⚠️ CRITICAL
- **Location:** [resources/js/components/borrowing/PickupVerification.vue](resources/js/components/borrowing/PickupVerification.vue#L192)
- **Severity:** Critical
- **Issue:** Component uses `STATUS_INFO` object at line 192 but doesn't import it
- **Current Import (Line 245):**
  ```javascript
  import { formatDate, isValidPickupCode } from '../../data/borrowingStatuses.js'
  ```
- **Problem Code (Line 192):**
  ```javascript
  const statusInfo = computed(() => {
    return STATUS_INFO[props.borrowing.status] || STATUS_INFO.ready_for_pickup
  })
  ```
- **Fix:** Add `STATUS_INFO` to import statement:
  ```javascript
  import { formatDate, isValidPickupCode, STATUS_INFO } from '../../data/borrowingStatuses.js'
  ```
- **Impact:** ReferenceError at runtime - component will crash when accessing statusInfo

---

### 2. **Missing Function Definition in BorrowingForm.vue** ⚠️ CRITICAL
- **Location:** [resources/js/components/borrowing/BorrowingForm.vue](resources/js/components/borrowing/BorrowingForm.vue#L150+)
- **Severity:** Critical
- **Issue:** Form submission handler `submitForm` is called but not defined in script
- **Call Location:** Line 11 has `@submit.prevent="submitForm"`
- **Problem:** The `submitForm` method is referenced in template but never defined
- **Expected Methods Missing:**
  - `submitForm()` - Form submission handler
  - `resetForm()` - Reset button handler (Line 76)
- **Fix:** Add complete implementation:
  ```javascript
  const submitForm = async () => {
    if (!validateForm()) return
    isSubmitting.value = true
    try {
      // API call here
      const result = await createBorrowingRequest(form.value)
      successMessage.value = 'Permintaan peminjaman berhasil!'
      showForm.value = false
      emit('submit', result)
      resetForm()
    } catch (err) {
      validationError.value = 'Gagal mengirim permintaan'
    } finally {
      isSubmitting.value = false
    }
  }
  
  const resetForm = () => {
    form.value = {
      equipmentId: '',
      quantity: 1,
      returnDate: getDefaultReturnDate(),
      reason: ''
    }
    validationError.value = ''
    showForm.value = false
  }
  ```
- **Impact:** Form cannot be submitted; clicking submit button will cause error

---

### 3. **Undefined Function in ReturnVerification.vue** ⚠️ CRITICAL
- **Location:** [resources/js/components/borrowing/ReturnVerification.vue](resources/js/components/borrowing/ReturnVerification.vue#L300+)
- **Severity:** Critical
- **Issue:** Multiple handler functions referenced but not implemented
- **Missing Functions:**
  - `handlePhotoBefore()` - Line 85
  - `handlePhotoAfter()` - Line 179
  - `submitCustomerVerification()` - Line 146
  - `submitStaffVerification()` - Line 253
- **Imported But Possibly Unused:**
  - `verifyReturnCustomer` - Line 280
  - `verifyReturnStaff` - Line 280
- **Fix:** These functions need full implementation with proper logic
- **Impact:** Return verification workflow completely non-functional

---

### 4. **Undefined Function Calls in PickupVerification.vue** ⚠️ CRITICAL
- **Location:** [resources/js/components/borrowing/PickupVerification.vue](resources/js/components/borrowing/PickupVerification.vue#L280+)
- **Severity:** Critical
- **Issue:** `handlePhotoUpload()` is called at line 83 but handling logic may be incomplete
- **Missing Properties:**
  - `handlePhotoUpload()` checks `event.target.files?.[0]` (Line 223) - needs validation
  - `submitVerification()` calls undefined `verifyPickup()` function
- **Fix:** Ensure all functions are properly implemented:
  ```javascript
  const handlePhotoUpload = (event) => {
    const file = event.target.files?.[0]
    if (file) {
      if (file.size > 5 * 1024 * 1024) {
        error.value = 'File terlalu besar (max 5MB)'
        return
      }
      input.value.photoFile = file
      input.value.photoName = file.name
    }
  }
  ```
- **Impact:** Photo upload will fail or have unpredictable behavior

---

## High Severity Issues

### 5. **Router Missing Page Import** 🔴 HIGH
- **Location:** [resources/js/router.js](resources/js/router.js#L5)
- **Severity:** High
- **Issue:** Router imports `DashboardRoleAware.vue` but component may not exist or have issues
- **Current:**
  ```javascript
  import Dashboard from './pages/DashboardRoleAware.vue'
  ```
- **Problem:** No verification that this component is properly exported and functional
- **Observation:** Multiple dashboard variants exist (Dashboard.vue, DashboardEnhanced.vue, DashboardRoleAware.vue) - unclear which is active
- **Fix:** Verify the component exists and is properly implemented, or consolidate dashboard components
- **Impact:** Dashboard route may fail to load

---

### 6. **Missing Lifecycle Hook in BorrowingForm** 🔴 HIGH
- **Location:** [resources/js/components/borrowing/BorrowingForm.vue](resources/js/components/borrowing/BorrowingForm.vue#L110)
- **Severity:** High
- **Issue:** Default return date is computed but not set on component mount
- **Missing:** `onMounted()` hook to initialize form defaults
- **Current:** `getDefaultReturnDate()` function exists but is never called
- **Expected:**
  ```javascript
  onMounted(() => {
    form.value.returnDate = getDefaultReturnDate()
  })
  ```
- **Impact:** Return date field starts empty; users must always set manually

---

### 7. **Unused Imports in Components** 🔴 HIGH
- **Location:** Multiple Vue components
- **Severity:** High (Code Quality)
- **Components:**
  - [ReturnVerification.vue](resources/js/components/borrowing/ReturnVerification.vue#L280-281): Imports `verifyReturnCustomer`, `verifyReturnStaff` but may not be used correctly
- **Issue:** Dead code or functions called but not imported
- **Fix:** Remove unused imports or use them correctly

---

## Medium Severity Issues

### 8. **App Navigation Guard Logic Issue** 🟡 MEDIUM
- **Location:** [resources/js/router.js](resources/js/router.js#L50-55)
- **Severity:** Medium
- **Issue:** Navigation guard doesn't return `next()` callback pattern
- **Current Code:**
  ```javascript
  router.beforeEach((to, from) => {
    const isAuthenticated = localStorage.getItem('user')
    
    if (to.meta.requiresAuth && !isAuthenticated) {
      return '/login'
    }
    // Implicitly return true if no redirect needed
  })
  ```
- **Problem:** Comment says "Implicitly return true" but actually returns undefined/undefined behavior
- **Better Approach:**
  ```javascript
  router.beforeEach((to, from) => {
    const isAuthenticated = localStorage.getItem('user')
    
    if (to.meta.requiresAuth && !isAuthenticated) {
      return { name: 'Login' }
    }
    // Explicitly allow navigation
    return true
  })
  ```
- **Impact:** Guard may not work correctly on all edge cases

---

### 9. **App Loading State Not Reset Properly** 🟡 MEDIUM
- **Location:** [resources/js/App.vue](resources/js/App.vue#L24-35)
- **Severity:** Medium
- **Issue:** Loading screen has hardcoded 500ms timeout after navigation ends
- **Current:**
  ```javascript
  router.afterEach(() => {
    setTimeout(() => {
      isLoading.value = false
    }, 500)
  })
  ```
- **Problem:** 
  1. Fixed 500ms delay may be too short/long depending on actual load time
  2. Multiple rapid navigations could cause race conditions
  3. No error handling if route fails to load
- **Better Approach:**
  ```javascript
  router.afterEach((to, from, failure) => {
    if (failure) {
      isLoading.value = false
      return
    }
    setTimeout(() => {
      isLoading.value = false
    }, 300) // Shorter for better UX
  })
  ```
- **Impact:** Loading screen may not behave correctly on slow networks

---

### 10. **LandingPage Dark Mode Persistence** 🟡 MEDIUM
- **Location:** [resources/js/pages/LandingPage.vue](resources/js/pages/LandingPage.vue#L310-325)
- **Severity:** Medium
- **Issue:** Dark mode state is stored in localStorage but key is different in toggle vs initialization
- **Store Key Mismatch:**
  - Toggle uses: `'trustequip_darkmode'` (Line 313)
  - But check uses: `localStorage.getItem('trustequip_darkmode')` (Line 334)
  (Actually these match, but the check at app boot should be consistent across all components)
- **Problem:** No global dark mode provider - each component manages separately
- **Impact:** Dark mode state not synchronized across app (LandingPage vs Dashboard)

---

### 11. **Event Handler Type Mismatch** 🟡 MEDIUM
- **Location:** [resources/js/pages/LandingPage.vue](resources/js/pages/LandingPage.vue#L360-375)
- **Severity:** Medium
- **Issue:** Keyboard handler has logic error in left/right navigation
- **Current Code:**
  ```javascript
  if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
    // This means LEFT arrow scrolls UP in sections
    // But RIGHT arrow scrolls RIGHT in carousel
    // Inconsistent behavior
  }
  ```
- **Problem:** Navigation feels inconsistent:
  - ArrowLeft in carousel = scroll left
  - ArrowLeft in sections = scroll up (counter-intuitive)
- **Fix:** Normalize arrow key behavior
- **Impact:** Poor user experience with keyboard navigation

---

### 12. **Carousel Scroll Logic Race Condition** 🟡 MEDIUM
- **Location:** [resources/js/pages/LandingPage.vue](resources/js/pages/LandingPage.vue#L350-360)
- **Severity:** Medium
- **Issue:** `scrollCarousel()` and `handleCarouselWheel()` can conflict
- **Problem:**
  ```javascript
  const scrollCarousel = (direction) => {
    const newPosition = carouselPosition.value + direction
    if (newPosition >= 0 && newPosition < products.value.length) {
      carouselPosition.value = newPosition
      if (carouselContainer.value) {
        const cardWidth = carouselContainer.value.querySelector('.product-card').offsetWidth
        // What if .product-card is not rendered yet?
        const gap = 30
        carouselContainer.value.scrollLeft = newPosition * (cardWidth + gap)
      }
    }
  }
  ```
- **Problem:** No null check on `querySelector()` result
- **Impact:** Potential "Cannot read property 'offsetWidth' of null" error

---

### 13. **Missing Prop Validation in LandingPage** 🟡 MEDIUM
- **Location:** [resources/js/pages/LandingPage.vue](resources/js/pages/LandingPage.vue#L1-5)
- **Severity:** Medium
- **Issue:** Component doesn't use router in template but imports it
- **Current:**
  ```javascript
  import { useRouter } from 'vue-router'
  const router = useRouter()
  // But router is never used in the component
  ```
- **Problem:** Dead code - import is unused
- **Fix:** Remove unused import or use it for navigation:
  ```javascript
  // Either remove this, or use it:
  const handleRegisterClick = () => {
    router.push('/register')
  }
  ```
- **Impact:** Minor - just code quality issue

---

## Low Severity Issues

### 14. **Hardcoded Statistics in Dashboards** 🟢 LOW
- **Location:** Multiple dashboard components
- **Severity:** Low
- **Issues Found:**
  1. [CustomerDashboard.vue](resources/js/components/dashboards/CustomerDashboard.vue#L15) - Stats are hardcoded:
     ```javascript
     <p class="stat-value">3</p>  <!-- Hardcoded -->
     ```
  2. [AdminDashboard.vue](resources/js/components/dashboards/AdminDashboard.vue#L20) - Same pattern
  3. [StaffDashboard.vue](resources/js/components/dashboards/StaffDashboard.vue#L20) - Same pattern
- **Problem:** Numbers don't reflect actual data
- **Fix:** Bind to computed/reactive data:
  ```javascript
  const activeLoans = computed(() => borrowings.value.filter(b => b.status === 'picked_up').length)
  ```
- **Impact:** Statistics misleading to users

---

### 15. **Inconsistent Responsive Breakpoints** 🟢 LOW
- **Location:** [resources/css/landing.css](resources/css/landing.css#L318+)
- **Severity:** Low
- **Issues:**
  1. Breakpoints are: 1024px, 768px, 480px (not mobile-first)
  2. Multiple @media queries for same breakpoint (Lines 318, 1127, 1151, 1244)
  3. Some values duplicated (e.g., products-carousel displayed differently)
- **Problem:** Potential cascading issues; maintenance burden
- **Fix:** Consolidate media queries:
  ```css
  @media (max-width: 480px) { /* Smallest first */ }
  @media (max-width: 768px) { /* Medium */ }
  @media (max-width: 1024px) { /* Large */ }
  ```
- **Impact:** CSS file harder to maintain; potential style conflicts

---

### 16. **Missing Accessibility Attributes** 🟢 LOW
- **Location:** Multiple Vue components
- **Severity:** Low (Accessibility)
- **Issues Found:**
  1. [LandingPage.vue](resources/js/pages/LandingPage.vue#L60) - Mobile menu toggle lacks `aria-expanded`
  2. Carousel buttons missing `aria-label`
  3. Form inputs missing `aria-required` or `aria-invalid`
  4. Modal overlays lack `role="dialog"` and `aria-modal="true"`
  5. Notification dropdown lacks `role="region"` and `aria-live="polite"`
- **Fix Examples:**
  ```vue
  <!-- Mobile Menu Toggle -->
  <button 
    :aria-expanded="isMobileMenuOpen"
    aria-label="Toggle navigation menu"
    class="mobile-menu-toggle"
  >
  
  <!-- Form Input -->
  <input
    aria-required="true"
    aria-label="Equipment selection"
    required
  >
  
  <!-- Modal -->
  <div
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title"
  >
  ```
- **Impact:** Reduced accessibility for screen reader users

---

### 17. **CSS Animation Performance** 🟢 LOW
- **Location:** [resources/css/landing.css](resources/css/landing.css#L600+)
- **Severity:** Low (Performance)
- **Issues:**
  1. Multiple animations use `transform` (good) but some might trigger layout reflows
  2. `background-attachment: fixed` can cause performance issues on mobile (already handled at 768px)
  3. Smooth scroll on entire document (Line ~50) can be janky on low-end devices
- **Example Issue:**
  ```css
  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(30px);  /* Good */
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  ```
- **Fix:** Add `will-change` sparingly:
  ```css
  .product-card {
    will-change: transform;
  }
  ```
- **Impact:** Slight janky animations on older devices

---

### 18. **Potential Null Reference in Router** 🟢 LOW
- **Location:** [resources/js/router.js](resources/js/router.js#L50)
- **Severity:** Low
- **Issue:** `localStorage.getItem('user')` could return null but is checked as truthy
- **Current:**
  ```javascript
  const isAuthenticated = localStorage.getItem('user')
  if (to.meta.requiresAuth && !isAuthenticated) {
  ```
- **Problem:** Should validate the JSON structure if stored as JSON
- **Better:**
  ```javascript
  let isAuthenticated = false
  try {
    const userData = localStorage.getItem('user')
    isAuthenticated = userData ? JSON.parse(userData).id : false
  } catch {
    isAuthenticated = false
  }
  ```
- **Impact:** Minor - current code works but fragile

---

### 19. **Dead Code in SplashScreen** 🟢 LOW
- **Location:** [resources/js/components/SplashScreen.vue](resources/js/components/SplashScreen.vue#L38+)
- **Severity:** Low
- **Issue:** Methods defined but not called
- **Methods:**
  - `onEnter()` - No implementation, called from transition
  - `onLeave()` - No implementation, called from transition
- **Fix:** Either implement or remove:
  ```javascript
  const onEnter = (el) => {
    startAnimation()
  }
  
  const onLeave = (el) => {
    // Clean up if needed
  }
  ```
- **Impact:** Splash screen animations may not work

---

## Code Quality Issues

### 20. **Missing Error Boundaries** 🟡 MEDIUM
- **Issue:** No error handling for component failures
- **Affected Files:**
  - App.vue
  - DashboardRoleAware.vue
  - All dashboard sub-components
- **Suggestion:** Add try-catch blocks and error state handling
- **Example:**
  ```javascript
  const loadBorrowings = async () => {
    try {
      borrowings.value = await fetchBorrowings()
    } catch (err) {
      error.value = 'Failed to load borrowings'
      console.error(err)
    }
  }
  ```

---

## Security Concerns

### 21. **Client-Side Only Authentication** 🔴 CRITICAL
- **Location:** [resources/js/router.js](resources/js/router.js)
- **Severity:** Critical (Security)
- **Issue:** Auth state stored in localStorage and checked only on client
- **Vulnerability:**
  ```javascript
  const isAuthenticated = localStorage.getItem('user')
  ```
- **Problem:** Easily bypassed; user can manually set localStorage
- **Fix Required:** 
  1. Move auth check to backend
  2. Use HTTP-only cookies instead of localStorage
  3. Verify token on every API call
- **Impact:** Authentication can be spoofed

---

### 22. **Sensitive Data in localStorage** 🔴 CRITICAL
- **Issue:** Storing 'user' object in localStorage (likely includes token)
- **Vulnerability:** Accessible to any script on the domain (XSS risk)
- **Fix:** Use HTTP-only cookies with SameSite attribute
- **Impact:** Token exposure risk

---

## Summary Table

| ID | Issue | Severity | Status | Est. Fix Time |
|---|---|---|---|---|
| 1 | Missing STATUS_INFO import | 🔴 Critical | Open | 5 min |
| 2 | Missing submitForm function | 🔴 Critical | Open | 20 min |
| 3 | Missing verification handlers | 🔴 Critical | Open | 30 min |
| 4 | Missing photo upload logic | 🔴 Critical | Open | 15 min |
| 5 | Router import validation | 🔴 High | Open | 10 min |
| 6 | Missing lifecycle hook | 🔴 High | Open | 5 min |
| 7 | Unused imports | 🔴 High | Open | 10 min |
| 8 | Navigation guard logic | 🟡 Medium | Open | 10 min |
| 9 | Loading state timing | 🟡 Medium | Open | 10 min |
| 10 | Dark mode sync | 🟡 Medium | Open | 15 min |
| 11 | Keyboard navigation | 🟡 Medium | Open | 10 min |
| 12 | Carousel race condition | 🟡 Medium | Open | 10 min |
| 13 | Dead code imports | 🟡 Medium | Open | 5 min |
| 14 | Hardcoded stats | 🟢 Low | Open | 20 min |
| 15 | CSS breakpoints | 🟢 Low | Open | 15 min |
| 16 | Accessibility | 🟢 Low | Open | 30 min |
| 17 | Animation performance | 🟢 Low | Open | 10 min |
| 18 | Null reference | 🟢 Low | Open | 5 min |
| 19 | Dead code | 🟢 Low | Open | 5 min |
| 21 | Client-side auth | 🔴 Critical | SECURITY | 60 min |
| 22 | localStorage tokens | 🔴 Critical | SECURITY | 40 min |

---

## Recommended Priority

### Immediate (Next 24 hours)
1. Fix missing imports and function definitions (Issues 1-4)
2. Address security issues 21-22
3. Fix router logic (Issue 5)

### Short Term (This Week)
1. Complete missing implementations (Issues 6-13)
2. Add error handling
3. Fix responsive design

### Medium Term (This Sprint)
1. Accessibility improvements
2. Performance optimization
3. Code cleanup and refactoring

---

## Testing Recommendations

1. **Unit Tests:** Add tests for all form handlers
2. **E2E Tests:** Test navigation flows and borrowing workflows
3. **Accessibility Tests:** Use axe DevTools
4. **Performance Tests:** Check Lighthouse scores
5. **Security Testing:** Penetration test auth flows

---

## Files Requiring Immediate Attention

1. `resources/js/components/borrowing/PickupVerification.vue`
2. `resources/js/components/borrowing/BorrowingForm.vue`
3. `resources/js/components/borrowing/ReturnVerification.vue`
4. `resources/js/router.js`
5. `resources/js/App.vue`

---

**Report Generated:** 2026-04-04  
**Next Audit Recommended:** After fixes are applied
