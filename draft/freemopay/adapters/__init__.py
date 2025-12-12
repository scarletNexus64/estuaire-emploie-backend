"""
Business Logic Adapters

Chaque adapter gère la logique métier spécifique d'un type de paiement.
"""

from .package_adapter import PackagePaymentAdapter
from .premium_adapter import PremiumPaymentAdapter
from .renewal_adapter import RenewalPaymentAdapter

__all__ = [
    'PackagePaymentAdapter',
    'PremiumPaymentAdapter',
    'RenewalPaymentAdapter',
]
