"""
New Payment Views - Phase 4 Refactoring

Architecture simplifiée basée sur:
- PaymentService: Init paiement (< 5s)
- CallbackHandler: Traitement webhooks FreeMoPay
- Adapters: Logique métier déléguée

Plus de polling Celery, juste des callbacks natifs FreeMoPay.
"""

from .package import PackagePaymentView
from .premium import PremiumPaymentView
from .renewal import RenewalPaymentView
from .callback import PaymentCallbackView
from .status import CheckPaymentStatusView
from .list import PaymentListView, UserPaymentListView

__all__ = [
    # Payment operations
    'PackagePaymentView',
    'PremiumPaymentView',
    'RenewalPaymentView',
    'PaymentCallbackView',

    # Status & listing
    'CheckPaymentStatusView',
    'PaymentListView',
    'UserPaymentListView',
]
