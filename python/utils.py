import string
import math
import random
def random_string(length):
        return ''.join(random.choice(string.ascii_uppercase + string.digits) for _ in range(length))
