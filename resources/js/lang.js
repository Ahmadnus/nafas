const STORAGE_KEY = 'frutta_lang_v1';

export const translations = {
    ar: {
        digital_menu: 'قائمة رقمية',
        open_now: 'مفتوح الآن — اطلب عبر واتساب',
        closed_now: 'مغلق حاليًا',
        view_cart: 'عرض السلة',
        your_order: 'طلبك',
        empty_cart: 'سلتك فارغة.',
        note_placeholder: 'أضف ملاحظة (مثال: بدون ثلج، عنوان التوصيل)...',
        total: 'الإجمالي',
        send_whatsapp: 'إرسال الطلب عبر واتساب',
        add_to_cart: 'أضف إلى السلة',
        required: 'مطلوب',
        no_items: 'لا توجد أصناف في هذا القسم بعد.',
        new_order: 'طلب جديد',
        note_label: 'ملاحظة',
        customer_name_label: 'اسم العميل',
        customer_address_label: 'العنوان',
        customer_details: 'بيانات العميل',
        customer_details_hint: 'الاسم والعنوان اختياريان، يمكنك تأكيد الطلب بدونهما.',
        customer_name_placeholder: 'الاسم (اختياري)',
        customer_address_placeholder: 'العنوان (اختياري)',
        confirm_order: 'تأكيد الطلب',
        back: 'رجوع',
        subtotal: 'المجموع الفرعي',
        best_seller: 'الأكثر مبيعًا',
        fresh: 'طازج',
        offer: 'عرض',
        sold_out: 'نفدت الكمية',
    },
    en: {
        digital_menu: 'Digital Menu',
        open_now: 'Open Now — Order via WhatsApp',
        closed_now: 'Currently Closed',
        view_cart: 'View Cart',
        your_order: 'Your Order',
        empty_cart: 'Your cart is empty.',
        note_placeholder: 'Add a note (e.g. less ice, delivery address)...',
        total: 'Total',
        send_whatsapp: 'Send Order via WhatsApp',
        add_to_cart: 'Add to Cart',
        required: 'Required',
        no_items: 'No items in this category yet.',
        new_order: 'New Order',
        note_label: 'Note',
        customer_name_label: 'Customer Name',
        customer_address_label: 'Address',
        customer_details: 'Customer Details',
        customer_details_hint: 'Name and address are optional — you can confirm without them.',
        customer_name_placeholder: 'Name (optional)',
        customer_address_placeholder: 'Address (optional)',
        confirm_order: 'Confirm Order',
        back: 'Back',
        subtotal: 'Subtotal',
        best_seller: 'Best Seller',
        fresh: 'Fresh',
        offer: 'Offer',
        sold_out: 'Sold Out',
    },
};

function loadLang() {
    return localStorage.getItem(STORAGE_KEY) || 'ar';
}

export default function langStore() {
    return {
        current: loadLang(),

        t(key) {
            return translations[this.current]?.[key] ?? translations.en[key] ?? key;
        },

        toggle() {
            this.current = this.current === 'ar' ? 'en' : 'ar';
            localStorage.setItem(STORAGE_KEY, this.current);
            document.documentElement.setAttribute('dir', this.current === 'ar' ? 'rtl' : 'ltr');
            document.documentElement.setAttribute('lang', this.current);
        },

        get dir() {
            return this.current === 'ar' ? 'rtl' : 'ltr';
        },

        pick(arValue, enValue) {
            if (this.current === 'ar') {
                return arValue || enValue;
            }
            return enValue || arValue;
        },

        get currency() {
            return window.APP_CURRENCY || (this.current === 'ar' ? 'ل.س' : 'SYP');
        },
    };
}
