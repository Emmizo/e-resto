"""
Chen Notation ERD for e-Resto
  - Entities   : blue-bordered rectangles
  - Relationships : teal diamonds
  - Attributes  : orange ovals (key = underlined)
  - Cardinality : 1 / N labels on lines, close to each entity
"""
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
from matplotlib.patches import Ellipse
import numpy as np

OUT = '/Users/kwizera/sites/laravel_project/e-resto/app/image/erd.png'

# ── canvas ───────────────────────────────────────────────────────
FW, FH = 400, 260

# ── colours (matching screenshot) ───────────────────────────────
E_EC  = '#4472C4'   # entity border – blue
R_EC  = '#2BBBAD'   # relationship diamond – teal
A_EC  = '#E8A800'   # attribute oval – amber/orange
LC    = '#AAAAAA'   # connecting line – grey
CC    = '#333333'   # cardinality text

# ── sizes ────────────────────────────────────────────────────────
EW, EH  = 26, 12    # entity box w/h
RW, RH  = 22, 11    # diamond w/h
AW, AH  = 15, 7     # attribute oval w/h
AD      = 21        # distance: entity centre → attribute centre

fig, ax = plt.subplots(figsize=(FW/4, FH/4))
ax.set_xlim(0, FW); ax.set_ylim(0, FH)
ax.axis('off')
fig.patch.set_facecolor('#FFFFFF')
ax.set_facecolor('#FFFFFF')

# ── drawing helpers ──────────────────────────────────────────────
def draw_entity(cx, cy, name):
    ax.add_patch(plt.Rectangle((cx-EW/2, cy-EH/2), EW, EH,
        fc='white', ec=E_EC, lw=2.2, zorder=3))
    ax.text(cx, cy, name, ha='center', va='center',
        fontsize=8.5, fontweight='bold', color='#111111', zorder=4)

def draw_rel(cx, cy, *words):
    pts = [[cx, cy+RH/2],[cx+RW/2, cy],[cx, cy-RH/2],[cx-RW/2, cy]]
    ax.add_patch(plt.Polygon(pts, fc='white', ec=R_EC, lw=1.8, zorder=3))
    ax.text(cx, cy, '\n'.join(words), ha='center', va='center',
        fontsize=6, color=R_EC, fontweight='bold', zorder=4, linespacing=1.1)

def draw_attr(cx, cy, name, key=False, derived=False, multi=False):
    ls = '--' if derived else '-'
    ax.add_patch(Ellipse((cx, cy), AW, AH,
        fc='white', ec=A_EC, lw=1.3, ls=ls, zorder=3))
    if multi:
        ax.add_patch(Ellipse((cx, cy), AW-2, AH-2,
            fc='white', ec=A_EC, lw=1.0, zorder=3))
    ax.text(cx, cy+.2, name, ha='center', va='center',
        fontsize=5.7, color='#444444',
        fontweight='bold' if key else 'normal', zorder=4)
    if key:
        w = len(name) * 3.1
        ax.plot([cx-w/2, cx+w/2], [cy-1.4, cy-1.4],
            color='#444', lw=0.8, zorder=4)

def ln(x1, y1, x2, y2):
    ax.plot([x1, x2], [y1, y2], color=LC, lw=1.2, zorder=2,
        solid_capstyle='round')

def card(x, y, txt):
    ax.text(x, y, txt, ha='center', va='center',
        fontsize=11, fontweight='bold', color=CC, zorder=5,
        fontfamily='DejaVu Sans')

def attr_at(ex, ey, angle_deg, name, **kw):
    """Draw attribute oval at angle_deg from entity (ex,ey) and connect with line."""
    rad = np.radians(angle_deg)
    acx = ex + AD * np.cos(rad)
    acy = ey + AD * np.sin(rad)
    ln(ex, ey, acx, acy)
    draw_attr(acx, acy, name, **kw)

# ════════════════════════════════════════════════════════════════
# LAYOUT  (cx, cy)
# Row 1 y=205 : USERS  RESTAURANTS  CUISINES
# Row 2 y=135 : ORDERS MENUS        MENU_ITEMS
# Row 3 y=60  :        ORDER_ITEMS
# ════════════════════════════════════════════════════════════════
U  = (65,  205)   # USERS
RS = (200, 205)   # RESTAURANTS
CS = (335, 205)   # CUISINES
OR = (65,  130)   # ORDERS
MN = (200, 130)   # MENUS
MI = (335, 130)   # MENU_ITEMS
OI = (200, 55)    # ORDER_ITEMS

# draw entities
for (cx,cy), name in [
    (U,'USERS'),(RS,'RESTAURANTS'),(CS,'CUISINES'),
    (OR,'ORDERS'),(MN,'MENUS'),(MI,'MENU_ITEMS'),(OI,'ORDER_ITEMS')]:
    draw_entity(cx, cy, name)

# ════════════════════════════════════════════════════════════════
# RELATIONSHIPS
# ════════════════════════════════════════════════════════════════
OWNS_d       = (132, 205)   # between USERS and RESTAURANTS
CLASSIF_d    = (267, 205)   # between RESTAURANTS and CUISINES
HAS_d        = (200, 167)   # between RESTAURANTS and MENUS (vertical)
CONTAINS_d   = (267, 130)   # between MENUS and MENU_ITEMS (horizontal)
PLACES_d     = (65,  167)   # between USERS and ORDERS (vertical)
INCLUDES_d   = (132, 92)    # between ORDERS and ORDER_ITEMS (diagonal)
ORDERED_d    = (267, 92)    # between MENU_ITEMS and ORDER_ITEMS (diagonal)

draw_rel(*OWNS_d,    'OWNS')
draw_rel(*CLASSIF_d, 'CLASSIFIED','BY')
draw_rel(*HAS_d,     'HAS')
draw_rel(*CONTAINS_d,'CONTAINS')
draw_rel(*PLACES_d,  'PLACES')
draw_rel(*INCLUDES_d,'INCLUDES')
draw_rel(*ORDERED_d, 'ORDERED','IN')

# ════════════════════════════════════════════════════════════════
# CONNECTING LINES  (centre-to-centre; shapes drawn on top hide line inside)
# ════════════════════════════════════════════════════════════════
# OWNS
ln(U[0], U[1], OWNS_d[0], OWNS_d[1])
ln(OWNS_d[0], OWNS_d[1], RS[0], RS[1])

# CLASSIFIED BY
ln(RS[0], RS[1], CLASSIF_d[0], CLASSIF_d[1])
ln(CLASSIF_d[0], CLASSIF_d[1], CS[0], CS[1])

# HAS  (vertical)
ln(RS[0], RS[1], HAS_d[0], HAS_d[1])
ln(HAS_d[0], HAS_d[1], MN[0], MN[1])

# CONTAINS (horizontal)
ln(MN[0], MN[1], CONTAINS_d[0], CONTAINS_d[1])
ln(CONTAINS_d[0], CONTAINS_d[1], MI[0], MI[1])

# PLACES (vertical)
ln(U[0], U[1], PLACES_d[0], PLACES_d[1])
ln(PLACES_d[0], PLACES_d[1], OR[0], OR[1])

# INCLUDES (diagonal: ORDERS → ORDER_ITEMS)
ln(OR[0], OR[1], INCLUDES_d[0], INCLUDES_d[1])
ln(INCLUDES_d[0], INCLUDES_d[1], OI[0], OI[1])

# ORDERED IN (diagonal: MENU_ITEMS → ORDER_ITEMS)
ln(MI[0], MI[1], ORDERED_d[0], ORDERED_d[1])
ln(ORDERED_d[0], ORDERED_d[1], OI[0], OI[1])

# ════════════════════════════════════════════════════════════════
# CARDINALITY  labels — placed ON the line, just outside entity border
# Convention: label appears between entity and its diamond
# ════════════════════════════════════════════════════════════════
# OWNS
card(93,  209, '1')    # users side
card(163, 209, 'N')    # restaurants side

# CLASSIFIED BY
card(228, 209, 'N')    # restaurants side
card(305, 209, '1')    # cuisines side

# HAS (vertical)
card(207, 195, '1')    # restaurants side (bottom of entity)
card(207, 143, 'N')    # menus side (top of entity)

# CONTAINS
card(228, 134, '1')    # menus side
card(305, 134, 'N')    # menu_items side

# PLACES (vertical)
card(72, 193, '1')     # users side
card(72, 143, 'N')     # orders side

# INCLUDES (diagonal orders→order_items)
card(88,  122, '1')    # orders side
card(175, 66,  'N')    # order_items side

# ORDERED IN (diagonal menu_items→order_items)
card(312, 120, '1')    # menu_items side
card(223, 66,  'N')    # order_items side

# ════════════════════════════════════════════════════════════════
# ATTRIBUTES
# angles: 0=right, 90=up, 180=left, 270=down
# choose angles that avoid relationship-line directions
# ════════════════════════════════════════════════════════════════

# USERS (65,205) — right goes to OWNS(132), down goes to PLACES(65,167)
# free directions: left(180°), top-left(135°), top(90°), bottom-left(225°)
attr_at(*U, 90,  'id',    key=True)
attr_at(*U, 130, 'email')
attr_at(*U, 165, 'role')
attr_at(*U, 210, 'phone')

# RESTAURANTS (200,205) — left to OWNS, right to CLASSIF, down to HAS
# free: top(90°), top-left(120°), top-right(60°)
attr_at(*RS, 60,  'id',    key=True)
attr_at(*RS, 90,  'name')
attr_at(*RS, 120, 'address')

# CUISINES (335,205) — left to CLASSIF
# free: right(0°), top(90°), bottom(270°)
attr_at(*CS, 70,  'id',   key=True)
attr_at(*CS, 30,  'name')

# ORDERS (65,130) — up to PLACES, right/diagonal to INCLUDES
# free: left(180°), bottom-left(225°), bottom(270°)
attr_at(*OR, 180, 'id',          key=True)
attr_at(*OR, 220, 'status')
attr_at(*OR, 255, 'total_amount')

# MENUS (200,130) — up to HAS, right to CONTAINS
# free: left(180°), bottom(270°), bottom-left(225°)
attr_at(*MN, 225, 'id',   key=True)
attr_at(*MN, 270, 'name')
attr_at(*MN, 195, 'is_active')

# MENU_ITEMS (335,130) — left to CONTAINS, diagonal to ORDERED_IN
# free: right(0°), top(90°), bottom(270°)
attr_at(*MI, 50,  'id',    key=True)
attr_at(*MI, 10,  'name')
attr_at(*MI, 330, 'price')
attr_at(*MI, 300, 'category')

# ORDER_ITEMS (200,55) — top-left to INCLUDES, top-right to ORDERED_IN
# free: bottom(270°), bottom-left(225°), bottom-right(315°)
attr_at(*OI, 270, 'id',      key=True)
attr_at(*OI, 230, 'quantity')
attr_at(*OI, 310, 'price')

# ════════════════════════════════════════════════════════════════
# LEGEND  (bottom-left)
# ════════════════════════════════════════════════════════════════
lx, ly = 6, 50
bw, bh = 56, 46
ax.add_patch(plt.Rectangle((lx, ly-bh), bw, bh,
    fc='white', ec='#CCCCCC', lw=.8, zorder=8))
ax.text(lx+bw/2, ly-.8, 'Notation Key', ha='center',
    fontsize=7.5, fontweight='bold', color='#1A237E', zorder=9)

# entity mini
ax.add_patch(plt.Rectangle((lx+1, ly-11), 14, 7,
    fc='white', ec=E_EC, lw=1.6, zorder=9))
ax.text(lx+8, ly-7.5, 'Entity', ha='center', va='center',
    fontsize=6, color='#111', fontweight='bold', zorder=9)
ax.text(lx+17, ly-7.5, 'Entity / Table', ha='left', va='center',
    fontsize=6.5, color='#333', zorder=9)

# relationship mini
pts2 = [[lx+8, ly-16],[lx+15,ly-20],[lx+8,ly-24],[lx+1,ly-20]]
ax.add_patch(plt.Polygon(pts2, fc='white', ec=R_EC, lw=1.4, zorder=9))
ax.text(lx+8, ly-20, 'R', ha='center', va='center',
    fontsize=5.5, color=R_EC, fontweight='bold', zorder=10)
ax.text(lx+17, ly-20, 'Relationship', ha='left', va='center',
    fontsize=6.5, color='#333', zorder=9)

# attribute mini
ax.add_patch(Ellipse((lx+8, ly-30), 13, 6,
    fc='white', ec=A_EC, lw=1.2, zorder=9))
ax.text(lx+8, ly-30, 'attr', ha='center', va='center',
    fontsize=5.5, color='#444', zorder=10)
ax.text(lx+17, ly-30, 'Attribute', ha='left', va='center',
    fontsize=6.5, color='#333', zorder=9)

# key attribute mini
ax.add_patch(Ellipse((lx+8, ly-38), 13, 6,
    fc='white', ec=A_EC, lw=1.2, zorder=9))
ax.text(lx+8, ly-38+.2, 'key', ha='center', va='center',
    fontsize=5.5, color='#444', fontweight='bold', zorder=10)
ax.plot([lx+4.5, lx+11.5],[ly-38-1.5, ly-38-1.5], color='#444', lw=.8, zorder=10)
ax.text(lx+17, ly-38, 'Key Attribute (PK)', ha='left', va='center',
    fontsize=6.5, color='#333', zorder=9)

# cardinality note
ax.text(lx+1, ly-44.5, '1 / N  =  Cardinality on each line end',
    ha='left', va='center', fontsize=6, color='#555', zorder=9)

# ── title ─────────────────────────────────────────────────────────
ax.text(FW/2, FH-3,
    'e-Resto  —  Entity Relationship Diagram',
    ha='center', va='top', fontsize=15, fontweight='bold', color='#1A237E')
ax.text(FW/2, FH-11,
    'Chen Notation  ·  rectangles = entities  ·  diamonds = relationships  ·  ovals = attributes',
    ha='center', va='top', fontsize=8, color='#546E7A')

plt.tight_layout(pad=.2)
plt.savefig(OUT, dpi=220, bbox_inches='tight', facecolor='white')
plt.close()
print(f'Saved → {OUT}')
