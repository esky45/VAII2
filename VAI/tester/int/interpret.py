import sys
import re
import argparse
import xml.etree.ElementTree as ET
from collections import deque


class Arg:
    def __init__(self, arg_type, value):
        self.type = arg_type
        self.value = value


class Instruction:
    def __init__(self, ins_order, ins_opcode):
        self.opcode = ins_opcode
        if not ins_order.isdigit():

            exit(32)
        self.order = ins_order
        self.args = []

    def add_argument(self, sub_elem_list):
        arg_count = len(sub_elem_list)
        for sub_element in sub_elem_list:
            arg_type = sub_element.get('type')
            arg_value = sub_element.text
            if sub_element.tag == 'arg1':
                self.args.insert(0, (Arg(arg_type, arg_value)))
            elif sub_element.tag == 'arg2':
                if arg_count < 2:

                    exit(32)
                self.args.insert(1, (Arg(arg_type, arg_value)))
            elif sub_element.tag == 'arg3':
                if arg_count < 3:

                    exit(32)
                self.args.insert(2, (Arg(arg_type, arg_value)))


class FrameStack:
    GF = list()
    LF = deque()
    TF = None

    def push_frame(self):
        if self.TF is None:
            exit(55)
        self.LF.append(self.TF)
        self.TF = None

    def pop_frame(self):
        try:
            self.LF[-1]
        except IndexError:
            exit(55)
        self.TF = self.LF.pop()

    def create_frame(self):
        self.TF = None
        self.TF = list()


class Variable:
    def __init__(self, name):
        self.name = name
        self.value = None
        self.type = None

    def change_value(self, var_type, value):
        if var_type is None and value is not None:
            exit(32)
        if self.type is None:
            self.value = value
            self.type = var_type
        elif self.type == var_type and var_type is not None:
            self.value = value
        else:
            print('Konverze typu')
            exit(53)


class Symbol:
    def __init__(self, symb_type, value):
        if symb_type in ['int', 'string', 'bool']:
            self.type = symb_type
            self.value = value
        else:
            exit(53)

    def values(self):
        return [self.type, self.value]


class SymbStack:
    stack = deque()

    def push(self, symb):
        self.stack.append(symb)

    def pop(self):
        try:
            self.stack[-1]
        except IndexError:
            exit(56)
        return self.stack.pop()


class Label:
    def __init__(self, ins_list_index, label_name):
        self.index = ins_list_index
        self.name = label_name


class Labels:
    labelList = []
    labelStack = deque()

    def add_label(self, label):
        self.labelList.append(label)

    def get_label_index(self, name):
        for label in self.labelList:
            if label.name == name:
                return label.index
        exit(52)

    def call(self, next_index, name):
    
        self.labelStack.append(next_index)
        return self.get_label_index(name)

    def return_call_index(self):
        try:
            self.labelStack[-1]
        except IndexError:
            exit(56)
        return self.labelStack.pop()  # vrati index dalsi instrukce


def var_to_symb(var_name):
  
    var = var_find(var_name)
    symb = Symbol(var.type, var.value)
    return symb


def arg_to_symb(arg):
  
    if arg.type == 'var':
        symb = var_to_symb(arg.value)
    else:
        symb = Symbol(arg.type, arg.value)
    return symb


def arg_check_type(arg, arg_type):

    if arg.type != arg_type:
        exit(53)


def arg_check_count(ins, arg_count):
    if len(ins.args) != arg_count:
        exit(53)  # nespravny pocet argumentu


def def_var(name):
    if name[:2] == 'GF':
        for var in frameStack.GF:  # kontrola redefinice
            if var.name == name[3:]:
                exit(52)
        frameStack.GF.append(Variable(name[3:]))

    elif name[:2] == 'LF':
        try:
            frameStack.LF[-1]  # kontrola zda existuje LF
        except IndexError:
            exit(55)
        for var in frameStack.LF[-1]:  # kontrola redefinice
            if var.name == name[3:]:
                exit(52)

        frameStack.LF.insert(-1, Variable(name[3:]))  # vlozeni na konec

    elif name[:2] == 'TF':
        if frameStack.TF is None:
            exit(55)
        for var in frameStack.TF:  # kontrola redefinice
            if var.name == name[3:]:
                exit(52)
        frameStack.TF.append(Variable(name[3:]))


def var_change_value(name, var_type, value):
    var = var_find(name)
    var.change_value(var_type, value)


def var_get_frame(var_name):

    frame = None
    if var_name[:2] == 'GF':
        frame = frameStack.GF
    elif var_name[:2] == 'LF':
        try:
            frameStack.LF[-1]  # kontrola zda existuje LF
        except IndexError:
            exit(55)
        frame = frameStack.LF[-1]
    elif var_name[:2] == 'TF':
        frame = frameStack.TF

    if frame is None:
        exit(55)
    return frame


def var_find(var_name):
  
    frame = var_get_frame(var_name)

    for var in frame:
        if var_name[3:] in var.name:
            return var
    exit(53)


def is_int(symb):
    if symb.type != 'int':
        return False
    else:
        try:
            int(symb.value)
        except ValueError:
            exit(32)
        return True


def is_bool(symb):
    if symb.type != 'bool':
        return False
    else:
        if symb.value in ['true', 'false']:
            return True
        exit(32)


def is_string(symb):
    if symb.type != 'string':
        return False
    else:
        return True


def get_bool(symb):
    if symb.value == 'true':
        return True
    elif symb.value == 'false':
        return False
    else:
        exit(32)


def interpret(ins, current_step):
    next_step = current_step + 1
    if ins.opcode == 'CREATEFRAME':
        arg_check_count(ins, 0)
        frameStack.create_frame()
    elif ins.opcode == 'PUSHFRAME':
        arg_check_count(ins, 0)
        frameStack.push_frame()
    elif ins.opcode == 'POPFRAME':
        arg_check_count(ins, 0)
        frameStack.pop_frame()
    elif ins.opcode == 'DEFVAR':
        arg_check_count(ins, 1)
        arg_check_type(ins.args[0], 'var')
        def_var(ins.args[0].value)
    elif ins.opcode == 'MOVE':
        arg_check_count(ins, 2)
        arg_check_type(ins.args[0], 'var')
        symb = arg_to_symb(ins.args[1])
        var_change_value(ins.args[0].value, symb.type, symb.value)
    elif ins.opcode == 'WRITE':
        arg_check_count(ins, 1)
        symb = arg_to_symb(ins.args[0])
        print(symb.value)

    elif ins.opcode == 'CALL':
        arg_check_count(ins, 1)
        arg_check_type(ins.args[0], 'label')
        return Labels.call(next_step, ins.args[0].value)
    elif ins.opcode == 'RETURN':
        arg_check_count(ins, 0)
        return Labels.return_call_index()
    elif ins.opcode == 'PUSHS':
        arg_check_count(ins, 1)
        symb = arg_to_symb(ins.args[0])
        SymbStack.push(symb)
    elif ins.opcode == 'POPS':
        arg_check_count(ins, 1)
        arg_check_type(ins.args[0], 'var')
        symb = SymbStack.pop()
        var_change_value(ins.args[0].value, symb.type, symb.value)
    # aritmeticke
    elif ins.opcode == 'ADD':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        if not (is_int(symb1) and is_int(symb2)):
            exit(53)
        result = int(symb1.value) + int(symb2.value)
        var_change_value(ins.args[0].value, 'int', result)
    elif ins.opcode == 'SUB':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        if not (is_int(symb1) and is_int(symb2)):
            exit(53)
        result = int(symb1.value) - int(symb2.value)
        var_change_value(ins.args[0].value, 'int', result)
    elif ins.opcode == 'MUL':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        if not (is_int(symb1) and is_int(symb2)):
            exit(53)
        result = int(symb1.value) * int(symb2.value)
        var_change_value(ins.args[0].value, 'int', result)
    elif ins.opcode == 'IDIV':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        if not (is_int(symb1) and is_int(symb2)):
            exit(53)
        result = int(symb1.value) // int(symb2.value)
        var_change_value(ins.args[0].value, 'int', result)
    elif ins.opcode == 'LT':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        result = False
        if is_int(symb1) and is_int(symb2):
            result = int(symb1.value) < int(symb2.value)
        elif is_bool(symb1) and is_bool(symb2):
            result = get_bool(symb1) < get_bool(symb2)
        elif is_string(symb1) and is_string(symb2):
            result = symb1.value.lower() < symb2.value.lower()
        else:
            exit(53)
        var_change_value(ins.args[0].value, symb1.type, result)
    elif ins.opcode == 'GT':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        result = False
        if is_int(symb1) and is_int(symb2):
            result = int(symb1.value) > int(symb2.value)
        elif is_bool(symb1) and is_bool(symb2):
            result = get_bool(symb1) > get_bool(symb2)
        elif is_string(symb1) and is_string(symb2):
            result = symb1.value.lower() > symb2.value.lower()
        else:
            exit(53)
        var_change_value(ins.args[0].value, symb1.type, result)
    elif ins.opcode == 'EQ':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        result = False
        if is_int(symb1) and is_int(symb2):
            result = int(symb1.value) == int(symb2.value)
        elif is_bool(symb1) and is_bool(symb2):
            result = get_bool(symb1) == get_bool(symb2)
        elif is_string(symb1) and is_string(symb2):
            result = symb1.value.lower() == symb2.value.lower()
        elif symb1.type == 'nil' or symb2.type == 'nil':
            result = symb1.value.lower() == symb2.value.lower() and symb2.type == 'nil' and symb1 == 'nil'
        else:
            exit(53)
        var_change_value(ins.args[0].value, symb1.type, result)
    elif ins.opcode == 'AND':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        if not (is_bool(symb1) and is_bool(symb2)):
            exit(53)
        result = get_bool(symb1) and get_bool(symb2)
        var_change_value(ins.args[0].value, 'bool', result)
    elif ins.opcode == 'OR':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        if not (is_bool(symb1) and is_bool(symb2)):
            exit(53)
        result = get_bool(symb1) or get_bool(symb2)
        var_change_value(ins.args[0].value, 'bool', result)
    elif ins.opcode == 'NOT':
        arg_check_count(ins, 2)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        if not (is_bool(symb1)):
            exit(53)
        result = not get_bool(symb1)
        var_change_value(ins.args[0].value, 'bool', result)
    elif ins.opcode == 'INT2CHAR':
        arg_check_count(ins, 2)
        arg_check_type(ins.args[0], 'var')
        symb = arg_to_symb(ins.args[1])
        try:
            chr(int(symb.value))
        except ValueError:
            exit(58)
        var_change_value(ins.args[0].value, 'string', chr(int(symb.value)))
    elif ins.opcode == 'STRI2INT':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        if not (is_string(symb1) and is_int(symb2)):
            exit(53)
        try:
            symb1.value[int(symb2.value)]
        except IndexError:
            exit(58)
        var_change_value(ins.args[0].value, 'int', ord(symb1.value[int(symb2.value)]))

    elif ins.opcode == 'READ':
        arg_check_count(ins, 2)
        arg_check_type(ins.args[0], 'var')
        arg_check_type(ins.args[1], 'type')
        read = inputFile.readline(99).strip()
        var_change_value(ins.args[0].value, args[1].value, read)
    elif ins.opcode == 'CONCAT':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        if not (is_string(symb1) and is_string(symb2)):
            exit(53)
        var_change_value(ins.args[0].value, 'string', symb1.value + symb2.value)
    elif ins.opcode == 'STRLEN':
        arg_check_count(ins, 2)
        arg_check_type(ins.args[0], 'var')
        symb = arg_to_symb(ins.args[1])
        if not is_string(symb):
            exit(53)
        var_change_value(ins.args[0].value, 'int', len(symb.value))
    elif ins.opcode == 'GETCHAR':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        if not (is_string(symb1) and is_int(symb2)):
            exit(53)
        try:
            symb1.value[int(symb2.value)]
        except IndexError:
            exit(58)
        var_change_value(ins.args[0].value, 'string', symb1.value[int(symb2.value)])
    elif ins.opcode == 'SETCHAR':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])
        new = arg_to_symb(ins.args[0])
        if not (is_int(symb1) and is_string(symb2) and is_string(new)):
            exit(58)
        try:
            symb1.value[int(symb2.value)]
        except IndexError:
            exit(58)
        new.value[int(symb1.value)] = symb2.value[0]
        var_change_value(ins.args[0].value, 'string', new)
    elif ins.opcode == 'TYPE':
        arg_check_count(ins, 2)
        arg_check_type(ins.args[0], 'var')
        symb1 = arg_to_symb(ins.args[1])
        var_change_value(ins.args[0].value, 'type', symb1.type)
    elif ins.opcode == 'LABEL':
        arg_check_count(ins, 1)
        arg_check_type(ins.args[0], 'label')
    elif ins.opcode == 'JUMP':
        arg_check_count(ins, 1)
        arg_check_type(ins.args[0], 'label')
        return Labels.get_label_index(ins.args[0].value)
    elif ins.opcode == 'JUMPIFEQ':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'label')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])

        result = False
        if is_int(symb1) and is_int(symb2):
            result = int(symb1.value) == int(symb2.value)
        elif is_bool(symb1) and is_bool(symb2):
            result = get_bool(symb1) == get_bool(symb2)
        elif is_string(symb1) and is_string(symb2):
            result = symb1.value.lower() == symb2.value.lower()
        elif symb1.type == 'nil' or symb2.type == 'nil':
            result = symb1.value.lower() == symb2.value.lower() and symb2.type == 'nil'
        else:
            exit(53)
        if result:
            return Labels.get_label_index(ins.args[0].value)

    elif ins.opcode == 'JUMPIFNEQ':
        arg_check_count(ins, 3)
        arg_check_type(ins.args[0], 'label')
        symb1 = arg_to_symb(ins.args[1])
        symb2 = arg_to_symb(ins.args[2])

        result = False
        if is_int(symb1) and is_int(symb2):
            result = int(symb1.value) == int(symb2.value)
        elif is_bool(symb1) and is_bool(symb2):
            result = get_bool(symb1) == get_bool(symb2)
        elif is_string(symb1) and is_string(symb2):
            result = symb1.value.lower() == symb2.value.lower()
        elif symb1.type == 'nil' or symb2.type == 'nil':
            result = symb1.value.lower() == symb2.value.lower() and symb2.type == 'nil'
        else:
            exit(53)
        if not result:
            return Labels.get_label_index(ins.args[0].value)
    elif ins.opcode == 'EXIT':
        arg_check_count(ins, 1)
        symb = arg_to_symb(ins.args[0])
        if not is_int(symb):
            exit(53)
        if 0 <= int(symb.value) <= 49:
            exit(int(symb.value))
        else:
            exit(57)
    elif ins.opcode == 'DPRINT':
        arg_check_count(ins, 1)
        symb = arg_to_symb(ins.args[0])
    elif ins.opcode == 'BREAK':
        arg_check_count(ins, 1)
        symb = arg_to_symb(ins.args[0])
    else:
        exit(32)
    return next_step


# argparse
aParser = argparse.ArgumentParser(add_help=False)
aParser.add_argument("--source", nargs=1, help="|!Povinny argument! zadanie cesty zdrojoveho suboru, ak sa nezada bude nacitana z STDIN")
aParser.add_argument("--input", nargs=1, help="|!Povinny argument! zadanie cesty vstupneho suboru, ak sa nezada bude nacitana z STDIN")
aParser.add_argument('--help', action='help', default=argparse.SUPPRESS, help='|zobrazi tuto napoveda .')

args = aParser.parse_args()

if args.source is None and args.input is None:

    exit(10)

if args.source:
    sourceFile = args.source[0]
else:
    sourceFile = sys.stdin

if args.input:
    inputFile = open(args.input[0], 'r')
else:
    inputFile = sys.stdin

# xml load

try:
    tree = ET.parse(sourceFile)
except ET.ParseError:
    exit(31)

try:
    tree
except NameError:
    exit(31)
root = tree.getroot()

# xml check
if root.tag != 'program':  # hlavicka
    exit(32)  # TODO chyba
if root.get('language') != 'IPPcode21':
    sys.exit(32)

instructionList = list()  # list instrukci

for element in root:

    if element.tag != 'instruction':
        exit(32)
    attributes = list(element.attrib.keys())

    if not ('order' in attributes) or not ('opcode' in attributes):
        exit(32)

    order = element.get('order')
    opcode = element.get('opcode')
    instruction = Instruction(order, opcode)
    subElemList = []
    for subElement in element:
        if not (re.match(r'arg[123]', subElement.tag)):
            exit(32)
        if len(subElement.attrib) != 1:
            exit(32)
        if 'type' not in subElement.attrib:
            exit(32)
        subElemList.append(subElement)
    instruction.add_argument(subElemList)

    instructionList.append(instruction)

instructionList = sorted(instructionList, key=lambda ins: int(ins.order))

set_order = []  # list jiz nastavenych orderu
for i in instructionList:
    if int(i.order) < 1:
        exit(32)
    if i.order in set_order:
        exit(32)
    set_order.append(i.order)


frameStack = FrameStack()
Labels = Labels()
SymbStack = SymbStack()
step = 0
index = 0
for i in instructionList:
    if i.opcode == 'LABEL':
        Labels.add_label(Label(index, i.args[0].value))
    index = index + 1


while step != len(instructionList):

    step = interpret(instructionList[step], step)

